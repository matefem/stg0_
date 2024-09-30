<?php

namespace WPStaging\Pro\Backup\Task\Tasks\JobBackup;

use RuntimeException;
use WPStaging\Backup\Dto\StepsDto;
use WPStaging\Backup\Entity\BackupMetadata;
use WPStaging\Backup\Entity\MultipartMetadata;
use WPStaging\Backup\Service\BackupMetadataEditor;
use WPStaging\Backup\Task\Tasks\JobBackup\BackupMuPluginsTask;
use WPStaging\Backup\Task\Tasks\JobBackup\BackupOtherFilesTask;
use WPStaging\Backup\Task\Tasks\JobBackup\BackupPluginsTask;
use WPStaging\Backup\Task\Tasks\JobBackup\BackupThemesTask;
use WPStaging\Backup\Task\Tasks\JobBackup\BackupUploadsTask;
use WPStaging\Backup\Task\Tasks\JobBackup\DatabaseBackupTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\BackupOtherWpRootFilesTask;
use WPStaging\Backup\Task\Tasks\JobBackup\FinalizeBackupTask as BasicFinalizeBackupTask;
use WPStaging\Framework\Analytics\Actions\AnalyticsBackupCreate;
use WPStaging\Framework\Filesystem\PathIdentifier;
use WPStaging\Framework\Queue\SeekableQueueInterface;
use WPStaging\Framework\SiteInfo;
use WPStaging\Framework\Utils\Cache\BufferedCache;
use WPStaging\Framework\Utils\Cache\Cache;
use WPStaging\Pro\Backup\Service\Archiver;
use WPStaging\Vendor\Psr\Log\LoggerInterface;

class FinalizeBackupTask extends BasicFinalizeBackupTask
{
    /** @var Archiver */
    protected $archiver;

    /**
     * @param Archiver $archiver
     * @param BufferedCache $sqlCache
     * @param LoggerInterface $logger
     * @param Cache $cache
     * @param StepsDto $stepsDto
     * @param SeekableQueueInterface $taskQueue
     * @param PathIdentifier $pathIdentifier
     * @param BackupMetadataEditor $backupMetadataEditor
     * @param AnalyticsBackupCreate $analyticsBackupCreate
     * @param SiteInfo $siteInfo
     */
    public function __construct(
        Archiver $archiver,
        BufferedCache $sqlCache,
        LoggerInterface $logger,
        Cache $cache,
        StepsDto $stepsDto,
        SeekableQueueInterface $taskQueue,
        PathIdentifier $pathIdentifier,
        BackupMetadataEditor $backupMetadataEditor,
        AnalyticsBackupCreate $analyticsBackupCreate,
        SiteInfo $siteInfo
    ) {
        parent::__construct($archiver, $sqlCache, $logger, $cache, $stepsDto, $taskQueue, $pathIdentifier, $backupMetadataEditor, $analyticsBackupCreate, $siteInfo);
    }

    /**
     * @param BackupMetadata $backupMetadata
     * @param bool $isUploadBackup
     * @return void
     * @throws RuntimeException
     */
    protected function addSplitMetadata(BackupMetadata $backupMetadata, bool $isUploadBackup)
    {
        $backupsDirectory = $this->getFinalBackupParentDirectory();

        $filesToUpload = [];

        $splitMetadata = new MultipartMetadata();

        foreach ($this->jobDataDto->getMultipartFilesInfo() as $backupFileInfo) {
            $destinationFile = $backupFileInfo['destination'];
            $destination     = $backupsDirectory . $destinationFile;

            if ($isUploadBackup) {
                $filesToUpload[$destinationFile] = $destination;
            }

            $dbExtension = DatabaseBackupTask::FILE_FORMAT;
            $dbIdentifier = DatabaseBackupTask::PART_IDENTIFIER;
            if (preg_match("#.{$dbIdentifier}(.[0-9]+)?.{$dbExtension}$#", $destinationFile)) {
                $splitMetadata->pushBackupPart('database', $destinationFile);
                continue;
            }

            if ($this->checkPartByIdentifier(BackupMuPluginsTask::IDENTIFIER, $destinationFile)) {
                $splitMetadata->pushBackupPart('muplugins', $destinationFile);
                continue;
            }

            if ($this->checkPartByIdentifier(BackupPluginsTask::IDENTIFIER, $destinationFile)) {
                $splitMetadata->pushBackupPart('plugins', $destinationFile);
                continue;
            }

            if ($this->checkPartByIdentifier(BackupThemesTask::IDENTIFIER, $destinationFile)) {
                $splitMetadata->pushBackupPart('themes', $destinationFile);
                continue;
            }

            if ($this->checkPartByIdentifier(BackupUploadsTask::IDENTIFIER, $destinationFile)) {
                $splitMetadata->pushBackupPart('uploads', $destinationFile);
                continue;
            }

            if ($this->checkPartByIdentifier(BackupOtherFilesTask::IDENTIFIER, $destinationFile)) {
                $splitMetadata->pushBackupPart('others', $destinationFile);
            }

            if ($this->checkPartByIdentifier(BackupOtherWpRootFilesTask::IDENTIFIER, $destinationFile)) {
                $splitMetadata->pushBackupPart('otherWpRoot', $destinationFile);
            }
        }

        $this->jobDataDto->setFilesToUpload($filesToUpload);
        $backupMetadata->setMultipartMetadata($splitMetadata);
    }

    /**
     * @param BackupMetadata $backupMetadata
     * @return void
     */
    protected function addMultisiteMetadata(BackupMetadata $backupMetadata)
    {
        if (!$this->jobDataDto->getIsNetworkSiteBackup()) {
            $backupMetadata->setSites($this->jobDataDto->getSitesToBackup());
        }

        if ($this->jobDataDto->getIsNetworkSiteBackup()) {
            $backupMetadata->setBackupType(is_main_site($this->jobDataDto->getSubsiteBlogId()) ? BackupMetadata::BACKUP_TYPE_MAIN_SITE : BackupMetadata::BACKUP_TYPE_NETWORK_SUBSITE);
            $backupMetadata->setNetworkAdmins(get_super_admins());
            $backupMetadata->setBlogId($this->jobDataDto->getSubsiteBlogId());
        }
    }

    /**
     * @param BackupMetadata $backupMetadata
     * @param string $tmpSqlFilePath
     * @param string $destinationSqlFilePath
     * @return void
     * @throws RuntimeException
     */
    protected function addMetadataToSql(BackupMetadata $backupMetadata, string $tmpSqlFilePath, string $destinationSqlFilePath)
    {
        $sqlHandle = fopen($tmpSqlFilePath, 'a');
        fwrite($sqlHandle, PHP_EOL);
        fwrite($sqlHandle, '-- ' . json_encode($backupMetadata) . PHP_EOL);
        fclose($sqlHandle);

        if (!rename($tmpSqlFilePath, $destinationSqlFilePath)) {
            throw new RuntimeException("Cannot add metadata to Multipart SQL file.");
        }
    }

    /**
     * @param BackupMetadata $backupMetadata
     * @return void
     * @throws RuntimeException
     */
    protected function addMultipartInfoToMetadata(BackupMetadata $backupMetadata)
    {
        $multipartFilesInfo = $this->jobDataDto->getMultipartFilesInfo();
        if (empty($multipartFilesInfo)) {
            throw new RuntimeException('No multipart backup files to finalize.');
        }

        $destinationDirectory = $this->getFinalBackupParentDirectory();
        $destinationPath = $destinationDirectory . $this->currentFileInfo['destination'];
        if ($this->currentFileInfo['category'] === DatabaseBackupTask::PART_IDENTIFIER) {
            $this->addMetadataToSql($backupMetadata, $this->currentFileInfo['filePath'], $destinationPath);
            return;
        }

        $this->archiver->generateBackupMetadataForBackupPart($this->currentFileInfo['sizeBeforeAddingIndex'], $this->currentFileInfo['category'], $this->currentFileInfo['destination'], $this->currentFileInfo['index']);
        $this->jobDataDto->setCurrentMultipartFileInfoIndex($this->currentFileIndex + 1);
    }

    /**
     * @return void
     */
    protected function prepareArchiver()
    {
        $multipartFilesInfo     = $this->jobDataDto->getMultipartFilesInfo();
        $this->currentFileIndex = $this->jobDataDto->getCurrentMultipartFileInfoIndex();
        $this->currentFileInfo  = $multipartFilesInfo[$this->currentFileIndex];
        $this->archiver->setCategoryIndex($this->currentFileInfo['index'] ?? 0, false);
        $this->archiver->setCategory($this->currentFileInfo['category']);
        $this->archiver->setIsLocalBackup($this->jobDataDto->isLocalBackup());
    }
}
