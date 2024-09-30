<?php

namespace WPStaging\Pro\Backup\Job\Jobs;

use WPStaging\Backup\Job\Jobs\JobBackup as BasicJobBackup;
use WPStaging\Backup\Task\Tasks\JobBackup\IncludeDatabaseTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\BackupRequirementsCheckTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\CompressBackupTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\DatabaseBackupTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\FilesystemScannerTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\FinalizeBackupTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\AmazonS3StorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\DigitalOceanSpacesStorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\GenericS3StorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\GoogleDriveStorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\DropboxStorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\SftpStorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\WasabiStorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\ScheduleBackupTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\FinishBackupTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\BackupOtherWpRootFilesTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\ValidateBackupTask;

class JobBackup extends BasicJobBackup
{
    use RemoteUploadTasksTrait;

    /**
     * @return void
     */
    protected function addFinalizeTask()
    {
        $this->tasks[] = FinalizeBackupTask::class;
    }

    /**
     * @return void
     */
    protected function addFinishBackupTask()
    {
        $this->tasks[] = FinishBackupTask::class;
    }

    /**
     * @return void
     */
    protected function setRequirementTask()
    {
        $this->tasks[] = BackupRequirementsCheckTask::class;
    }


    protected function addDatabaseTasks()
    {
        if (!$this->jobDataDto->getIsExportingDatabase()) {
            return;
        }

        $this->tasks[] = DatabaseBackupTask::class;
        if (!$this->jobDataDto->getIsMultipartBackup()) {
            $this->tasks[] = IncludeDatabaseTask::class;
        }
    }

    protected function addCompressionTask()
    {
        // If compression is not enabled, early bail
        if (!$this->jobDataDto->getIsCompressedBackup()) {
            return;
        }

        // If the backup is in the old format, we don't support compression for it
        if ($this->jobDataDto->getIsBackupFormatV1()) {
            return;
        }

        // If the backup is multipart, we handle compression differently for it
        if ($this->jobDataDto->getIsMultipartBackup()) {
            return;
        }

        $this->tasks[] = CompressBackupTask::class;
    }

    protected function addValidationTasks()
    {
        if (!$this->jobDataDto->getIsMultipartBackup()) {
            $this->tasks[] = ValidateBackupTask::class;
        }

        foreach ($this->jobDataDto->getMultipartFilesInfo() as $ignored) {
            $this->tasks[] = ValidateBackupTask::class;
        }
    }

    /**
     * @return void
     */
    protected function setScannerTask()
    {
        $this->tasks[] = FilesystemScannerTask::class;
    }

    /**
     * @return void
     */
    protected function addSchedulerTask()
    {
        $this->tasks[] = ScheduleBackupTask::class;
    }

    /**
     * @return void
     */
    protected function addStoragesTasks()
    {
        if ($this->jobDataDto->isUploadToGoogleDrive()) {
            $this->tasks[] = GoogleDriveStorageTask::class;
        }

        if ($this->jobDataDto->isUploadToAmazonS3()) {
            $this->tasks[] = AmazonS3StorageTask::class;
        }

        if ($this->jobDataDto->isUploadToDropbox()) {
            $this->tasks[] = DropboxStorageTask::class;
        }

        if ($this->jobDataDto->isUploadToSftp()) {
            $this->tasks[] = SftpStorageTask::class;
        }

        if ($this->jobDataDto->isUploadToDigitalOceanSpaces()) {
            $this->tasks[] = DigitalOceanSpacesStorageTask::class;
        }

        if ($this->jobDataDto->isUploadToWasabi()) {
            $this->tasks[] = WasabiStorageTask::class;
        }

        if ($this->jobDataDto->isUploadToGenericS3()) {
            $this->tasks[] = GenericS3StorageTask::class;
        }
    }

    /**
     * @return void
     */
    protected function addBackupOtherWpRootFilesTasks()
    {
        if ($this->jobDataDto->getIsExportingOtherWpRootFiles()) {
            $this->tasks[] = BackupOtherWpRootFilesTask::class;
        }
    }
}
