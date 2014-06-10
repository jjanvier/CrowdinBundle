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
}
