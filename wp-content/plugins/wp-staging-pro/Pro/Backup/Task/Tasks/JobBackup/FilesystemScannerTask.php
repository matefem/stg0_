<?php

namespace WPStaging\Pro\Backup\Task\Tasks\JobBackup;

use DirectoryIterator;
use WPStaging\Core\WPStaging;
use WPStaging\Framework\Staging\Sites;
use WPStaging\Backup\Dto\TaskResponseDto;
use WPStaging\Backup\Task\Tasks\JobBackup\FilesystemScannerTask as BasicFilesystemScannerTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\BackupOtherWpRootFilesTask;

class FilesystemScannerTask extends BasicFilesystemScannerTask
{
    /**
     * @return array
     */
    protected function getExcludedDirectories(): array
    {
        $excludedDirs = parent::getExcludedDirectories();

        if (!$this->isBaseNetworkSite()) {
            return $excludedDirs;
        }

        $refresh = true;

        if ($this->jobDataDto->getIsNetworkSiteBackup()) {
            $excludedDirs[] = $this->directory->getUploadsDirectory($refresh) . 'sites';
            return $excludedDirs;
        }

        // Exclude all wp staging uploads directories from subsites
        $sitesDirectory = $this->directory->getUploadsDirectory($refresh) . 'sites';

        if (is_dir($sitesDirectory) === false) {
            return $excludedDirs;
        }

        $uploadsIt = new DirectoryIterator($sitesDirectory);

        foreach ($uploadsIt as $uploadItem) {
            // Early bail: We don't touch links
            if ($uploadItem->isLink() || $this->isDot($uploadItem)) {
                continue;
            }

            if ($uploadItem->isFile()) {
                continue;
            }

            if ($uploadItem->isDir()) {
                $excludedDirs[] = trailingslashit($uploadItem->getPathname()) . 'wp-staging';
            }
        }

        return $excludedDirs;
    }

    /**
     * @return string
     */
    protected function getUploadsDirectory(): string
    {
        if ($this->jobDataDto->getIsNetworkSiteBackup()) {
            switch_to_blog($this->jobDataDto->getSubsiteBlogId());
            $uploadsDir = $this->directory->getUploadsDirectory($refresh = true);
            restore_current_blog();

            return $uploadsDir;
        }

        return $this->directory->getMainSiteUploadsDirectory();
    }

    /**
     * @return bool
     */
    protected function isBaseNetworkSite(): bool
    {
        if (!is_multisite()) {
            return false;
        }

        $blogId = get_current_blog_id();
        return $blogId === 1 || $blogId === 0;
    }

    /**
     * Scan WP root directory(ABSPATH) but doesn't scan sub folders.
     *
     * @return TaskResponseDto
     */
    protected function scanWpRootDirectory(): TaskResponseDto
    {
        if (!$this->jobDataDto->getIsExportingOtherWpRootFiles()) {
            return $this->generateResponse();
        }

        $this->currentPathScanning = BackupOtherWpRootFilesTask::IDENTIFIER;
        $this->setupFileBackupQueue();

        $wpRootIt = new \DirectoryIterator($this->directory->getAbsPath());

        /** @var Sites */
        $stagingSites     = WPStaging::make(Sites::class);
        $stagingSitesDirs = $stagingSites->getStagingDirectories();

        $dirsToSkip = $this->directory->getWpDefaultRootDirectories();

        $dirsToSkip = array_merge($dirsToSkip, $stagingSitesDirs);
        $dirsToSkip = array_unique(array_merge($dirsToSkip, $this->jobDataDto->getBackupExcludedDirectories()));

        foreach ($wpRootIt as $wpRootFiles) {
            if ($wpRootFiles->isLink() || $this->isDot($wpRootFiles)) {
                continue;
            }

            if (!$wpRootFiles->isDir()) {
                continue;
            }

            if (!in_array($this->filesystem->normalizePath($wpRootFiles->getPathname(), true), $dirsToSkip)) {
                $this->enqueueDirToBeScanned($wpRootFiles);
            }
        }

        $this->unlockQueue();
        return $this->generateResponse();
    }
}
