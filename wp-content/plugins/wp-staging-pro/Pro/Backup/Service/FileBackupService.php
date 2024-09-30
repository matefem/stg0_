<?php

namespace WPStaging\Pro\Backup\Service;

use WPStaging\Backup\Service\FileBackupService as BaseFileBackupService;
use WPStaging\Framework\Filesystem\Filesystem;
use WPStaging\Framework\SiteInfo;

class FileBackupService extends BaseFileBackupService
{
    /** @var string */
    protected $fileIdentifier;

    /** @var Archiver */
    protected $archiver;

    /** @var bool */
    protected $isOtherWpRootFilesTask = false;

    public function __construct(Archiver $archiver, Filesystem $filesystem, SiteInfo $siteInfo)
    {
        parent::__construct($archiver, $filesystem, $siteInfo);
    }

    /**
     * @param string $fileIdentifier
     * @return void
     */
    public function setupArchiver(string $fileIdentifier, bool $isOtherWpRootFilesTask = false)
    {
        $this->fileIdentifier         = $fileIdentifier;
        $this->isOtherWpRootFilesTask = $isOtherWpRootFilesTask;

        if (!$this->jobDataDto->getIsMultipartBackup()) {
            $this->archiver->createArchiveFile(Archiver::CREATE_BINARY_HEADER);
            return;
        }

        $this->archiver->setCategory($this->fileIdentifier);
        if ($this->stepsDto->getTotal() > 0) {
            $indices = $this->jobDataDto->getFileBackupIndices();
            if (array_key_exists($this->fileIdentifier, $indices)) {
                $this->archiver->setCategoryIndex($indices[$this->fileIdentifier] ?? 0);
                return;
            }
        }

        $this->archiver->setCategoryIndex(0);
    }

    /**
     * @return void
     */
    protected function updateMultipartInfo()
    {
        if (!$this->jobDataDto->getIsMultipartBackup()) {
            return;
        }

        if ($this->stepsDto->isFinished() && $this->stepsDto->getTotal() > 0) {
            $backupPartInfo = $this->archiver->getFinalizeBackupInfo();
            $this->jobDataDto->addMultipartFileInfo($backupPartInfo);
        }
    }

    /**
     * @param string $path
     * @return void
     */
    protected function maybeIncrementPartNo(string $path)
    {
        if (!$this->jobDataDto->getIsMultipartBackup()) {
            return;
        }

        $fileSize = filesize($path);
        $maxPartSize = $this->jobDataDto->getMaxMultipartBackupSize();
        if (!$this->archiver->doExceedMaxPartSize($fileSize, $maxPartSize)) {
            return;
        }

        $backupPartInfo = $this->archiver->getFinalizeBackupInfo();
        $this->jobDataDto->addMultipartFileInfo($backupPartInfo);

        $index = 0;
        $fileBackupIndices = $this->jobDataDto->getFileBackupIndices();
        if (array_key_exists($this->fileIdentifier, $fileBackupIndices)) {
            $index = $fileBackupIndices[$this->fileIdentifier];
        }

        $fileBackupIndices[$this->fileIdentifier] = $index + 1;
        $this->jobDataDto->setFileBackupIndices($fileBackupIndices);
        $this->archiver->setCategoryIndex($fileBackupIndices[$this->fileIdentifier] ?? 0);
    }

    protected function shouldPrependAbsPath(): bool
    {
        return ($this->isOtherWpRootFilesTask || ($this->isWpContentOutsideAbspath === false));
    }
}
