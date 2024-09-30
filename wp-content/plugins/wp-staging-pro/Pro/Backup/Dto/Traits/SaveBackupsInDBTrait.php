<?php

namespace WPStaging\Pro\Backup\Dto\Traits;

use WPStaging\Core\WPStaging;
use WPStaging\Backup\BackupRetentionHandler;
use WPStaging\Backup\Dto\Interfaces\RemoteUploadDtoInterface;

trait SaveBackupsInDBTrait
{
    /**
     * @param string $backupId
     * @param RemoteUploadDtoInterface $jobDataDto
     * @return void
     */
    protected function saveBackupsInDB(string $backupId, RemoteUploadDtoInterface $jobDataDto)
    {
        if ($jobDataDto->getRepeatBackupOnSchedule()) {
            return;
        }

        $storages = $jobDataDto->getStorages();

        // Don't hold backup with only localStorage.
        if (count($storages) === 1 && reset($storages) === 'localStorage') {
            return;
        }

        /** @var BackupRetentionHandler $backupRetention */
        $backupRetention = WPStaging::make(BackupRetentionHandler::class);

        $oldBackups = $backupRetention->getBackupsRetention();
        if (isset($oldBackups[$backupId])) {
            $existingStorages = $oldBackups[$backupId]['storages'];
            $storages = array_unique(array_merge($existingStorages, $storages));
        }

        $oldBackups[$backupId] = [
            'createdDate' => current_time('Ymd-His'),
            'storages'    => $storages,
            'backupSize'  => $jobDataDto->getTotalBackupSize(),
            'isMultipart' => $jobDataDto->getIsMultipartBackup(),
        ];

        $backupRetention->updateBackupsRetentionOptions($oldBackups);
    }
}
