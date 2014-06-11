<?php

/*
 * This file is part of the Crowdin package.
 *
 * (c) Julien Janvier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jjanvier\Bundle\CrowdinBundle\Archive;

/**
 * Interface ArchiveInterface
 *
 * Handle the Crowdin archive.
 */
interface ArchiveInterface
{
    /**
     * Extracts the archive.

     * @return ArchiveInterface
     */
    public function extract();

    /**
     * Remove the archive

     * @return ArchiveInterface
     */
    public function remove();

    /**
     * Returns the list of files contained in the archive.
     *
     * @return array|\SplFileInfo
     */
    public function getFiles();

    /**
     * Get the path where the archive will be extracted
     *
     * @return string
     */
    public function getExtractPath();

    /**
     * Set the path where the archive will be extracted
     *
     * @param $extractPath
     *
     * @return ArchiveInterface
     */
    public function setExtractPath($extractPath);

    /**
     * Get the path of the archive
     *
     * @return string
     */
    public function getPath();

    /**
     * Set the path of the archive
     *
     * @param $path
     *
     * @return ArchiveInterface
     */
    public function setPath($path);

    /**
     * Get the filename of the archive
     *
     * @return string
     */
    public function getFilename();

    /**
     * Set the filename of the archive
     *
     * @param $filename
     *
     * @return ArchiveInterface
     */
    public function setFilename($filename);
}
