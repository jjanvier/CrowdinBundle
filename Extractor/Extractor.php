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
    protected $files = array();

    public function extract($archive, $dest)
    {
        $zip = new \ZipArchive();

        if (true !== $zip->open($archive)) {
            throw new \Exception('Impossible to open the archive %s', $archive);
        }

        $zip->extractTo($dest);

        for( $i = 0; $i < $zip->numFiles; $i++ ) {
            $path = sprintf('%s/%s', $dest, $zip->getNameIndex($i));

            if (is_file($path)) {
                $this->files[] = new SplFileInfo($path);
            }
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