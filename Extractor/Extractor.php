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

use \SplFileInfo;

class Extractor implements ExtractorInterface
{
    /**
     * @var array|\SplFileInfo
     */
    protected $files;

    public function extract($archive)
    {
        $zip = new \ZipArchive();

        if (true !== $zip->open($archive)) {
            throw new \Exception('Impossible to open the archive %s', $archive);
        }

        for( $i = 0; $i < $zip->numFiles; $i++ ) {
            $this->files[] = new SplFileInfo($zip->getNameIndex($i));
        }

        $zip->close();

        return $this;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }
} 