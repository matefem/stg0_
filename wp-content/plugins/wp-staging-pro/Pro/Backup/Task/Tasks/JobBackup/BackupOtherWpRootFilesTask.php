<?php

namespace WPStaging\Pro\Backup\Task\Tasks\JobBackup;

use WPStaging\Backup\Task\FileBackupTask;

class BackupOtherWpRootFilesTask extends FileBackupTask
{
    const IDENTIFIER = self::OTHER_WP_ROOT_IDENTIFIER;

    protected function getFileIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    /**
     * @return string
     */
    public static function getTaskName(): string
    {
        return parent::getTaskName() . '_' . self::IDENTIFIER;
    }

    /**
     * @return string
     */
    public static function getTaskTitle(): string
    {
        return 'Adding Other Files In WP Root to Backup';
    }

    /**
     * @return bool
     */
    protected function isOtherWpRootFilesTask(): bool
    {
        return true;
    }
}
