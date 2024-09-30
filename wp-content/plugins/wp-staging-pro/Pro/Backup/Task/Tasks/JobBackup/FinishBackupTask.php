<?php

namespace WPStaging\Pro\Backup\Task\Tasks\JobBackup;

use WPStaging\Backup\Task\Tasks\JobBackup\FinishBackupTask as BasicFinishBackupTask;
use WPStaging\Pro\Backup\Dto\Traits\SaveBackupsInDBTrait;

class FinishBackupTask extends BasicFinishBackupTask
{
    use SaveBackupsInDBTrait;

    /**
     * Retains backups, if at least one remote storage is set.
     *
     * @return void
     */
    protected function saveCloudStorageOptions()
    {
        $this->saveBackupsInDB($this->jobDataDto->getId(), $this->jobDataDto);
    }
}
