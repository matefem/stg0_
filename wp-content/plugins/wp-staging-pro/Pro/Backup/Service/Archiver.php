<?php

namespace WPStaging\Pro\Backup\Service;

use WPStaging\Backup\Dto\Job\JobBackupDataDto;
use WPStaging\Backup\Entity\BackupMetadata;
use WPStaging\Backup\Entity\MultipartMetadata;
use WPStaging\Backup\Service\Archiver as BaseArchiver;

class Archiver extends BaseArchiver
{
    /**
     * Category can be: empty string|null|false, plugins, mu-plugins, themes, uploads, other, database
     * Where empty string|null|false is used for single file backup,
     * And other is for files from wp-content not including plugins, mu-plugins, themes, uploads
     * @var string
     */
    private $category = '';

    /**
     * The current index of category in which appending files
     * Not used in single file backup
     * @var int
     */
    private $categoryIndex = 0;

    /**
     * Setup temp backup file and temp files index file for the given job id,
     * If multipart backup category and category index are given, then they are used to create unique file names
     * @return void
     */
    public function setupTmpBackupFile()
    {
        $additionalInfo = empty($this->category) ? '' : $this->category . '_' . $this->categoryIndex . '_';

        $postFix = $additionalInfo . $this->jobDataDto->getId();

        //debug_log("[Set Tmp Backup Files] File name postfix: " . $postFix);

        $this->tempBackup->setFilename('temp_wpstg_backup_' . $postFix);
        $this->tempBackup->setLifetime(DAY_IN_SECONDS);

        $tempBackupIndexFilePrefix = 'temp_backup_index_';
        $this->tempBackupIndex->setFilename($tempBackupIndexFilePrefix . $postFix);
        $this->tempBackupIndex->setLifetime(DAY_IN_SECONDS);
    }

    /**
     * @param int $index
     * @param bool $isCreateBinaryHeader
     * @return void
     */
    public function setCategoryIndex(int $index, bool $isCreateBinaryHeader = true)
    {
        if (empty($index)) {
            $index = 0;
        }

        $this->categoryIndex = $index;
        $this->setCategory($this->category, $isCreateBinaryHeader);
    }

    /**
     * @param string $category
     * @param bool $isCreateBinaryHeader
     * @return void
     */
    public function setCategory(string $category = '', bool $isCreateBinaryHeader = false)
    {
        $this->category = $category;

        $this->createArchiveFile($isCreateBinaryHeader);
    }

    /**
     * @param int $fileSize
     * @param int $maxPartSize
     * @return bool
     */
    public function doExceedMaxPartSize(int $fileSize, int $maxPartSize): bool
    {
        $allowedSize     = $fileSize - $this->archiverDto->getWrittenBytesTotal();
        $sizeAfterAdding = $allowedSize + filesize($this->tempBackup->getFilePath());
        return $sizeAfterAdding >= $maxPartSize;
    }

    /**
     * @param int    $sizeBeforeAddingIndex
     * @param string $category
     * @param string $partName
     * @param int    $categoryIndex
     */
    public function generateBackupMetadataForBackupPart(int $sizeBeforeAddingIndex, string $category, string $partName, int $categoryIndex)
    {
        $this->category      = $category;
        $this->categoryIndex = $categoryIndex;
        $this->setupTmpBackupFile();
        $this->generateBackupMetadata($sizeBeforeAddingIndex, $partName, $isBackupPart = true);
    }

    /**
     * @return array
     */
    public function getFinalizeBackupInfo(): array
    {
        return [
            'category'              => $this->category,
            'index'                 => $this->categoryIndex,
            'filePath'              => $this->tempBackup->getFilePath(),
            'destination'           => $this->getDestinationPath(),
            'status'                => 'Pending',
            'sizeBeforeAddingIndex' => 0
        ];
    }

    /**
     * @return string
     */
    public function getDestinationPath(): string
    {
        $extension = "wpstg";

        if ($this->category !== '') {
            $index     = $this->categoryIndex === 0 ? '' : ($this->categoryIndex . '.');
            $extension = $this->category . '.' . $index . $extension;
        }

        return sprintf(
            '%s_%s_%s.%s',
            parse_url(get_home_url())['host'],
            current_time('Ymd-His'),
            $this->jobDataDto->getId(),
            $extension
        );
    }

    /**
     * @param BackupMetadata $backupMetadata
     * @return void
     */
    protected function updateMultipartMetadata(BackupMetadata $backupMetadata)
    {
        /** @var JobBackupDataDto */
        $jobBackupDataDto = $this->jobDataDto;
        if (!$jobBackupDataDto->getIsMultipartBackup()) {
            return;
        }

        $splitMetadata = $backupMetadata->getMultipartMetadata();
        $splitMetadata = empty($splitMetadata) ? new MultipartMetadata() : $splitMetadata;
        $splitMetadata->setTotalFiles($jobBackupDataDto->getFilesInPart($this->category, $this->categoryIndex));
        $backupMetadata->setMultipartMetadata($splitMetadata);
    }

    protected function incrementFileCountInPart(JobBackupDataDto $jobBackupDataDto)
    {
        if (!$jobBackupDataDto->getIsMultipartBackup()) {
            return;
        }

        $filesCount = $jobBackupDataDto->getFilesInPart($this->category, $this->categoryIndex);
        $jobBackupDataDto->setFilesInPart($this->category, $this->categoryIndex, $filesCount + 1);
    }

    /**
     * @return void
     */
    protected function setIndexPositionCreated()
    {
        $this->archiverDto->setIndexPositionCreated(true, $this->category, $this->categoryIndex);
    }

    /**
     * @return bool
     */
    protected function isIndexPositionCreated(): bool
    {
        return $this->archiverDto->isIndexPositionCreated($this->category, $this->categoryIndex);
    }
}
