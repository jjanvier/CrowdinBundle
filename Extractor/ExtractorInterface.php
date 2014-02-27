<?php

/*
 * This file is part of the Crowdin package.
 *
 * (c) Julien Janvier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jjanvier\Bundle\CrowdinBundle\Extractor;

interface ExtractorInterface
{
    /**
     * Extracts the given archive.
     *
     * @param $archive
     * @param $dest
     * @return ExtractorInterface
     */
    public function extract($archive, $dest);

    /**
     * Returns the list of files contained in the archive.
     *
     * @return array|\SplFileInfo
     */
    public function getFiles();
} 