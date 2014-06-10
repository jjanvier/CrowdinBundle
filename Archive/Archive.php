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

use Jjanvier\Bundle\CrowdinBundle\Formatter\FormatterFactory;
use \SplFileInfo;

class Archive implements ArchiveInterface
{
    /**
     * @var array|\SplFileInfo
     */
    protected $files;

    /**
     * @var string path of the archive
     */
    protected $path;

    /**
     * @var string path the archive will be extracted
     */
    protected $destination;

    /**
     * @var bool tell if files of the archive should be cleaned after extraction
     */
    protected $clean;

    /**
     * @var string header to add to the files of the archive
     */
    protected $header;

    /**
     * Constructor
     *
     * @param string $path
     * @param string $destination
     * @param bool   $clean
     * @param string $header
     */
    public function __construct($path, $destination, $clean = false, $header = null)
    {
        $this->files = array();
        $this->path = $path;
        $this->destination = $destination;
        $this->header = $header;
        $this->clean = $clean;
    }

    /**
     * {@inheritdoc}
     */
    public function extract()
    {
        $zip = new \ZipArchive();

        if (true !== $zip->open($this->path)) {
            throw new \Exception('Impossible to open the archive %s', $this->path);
        }

        $zip->extractTo($this->destination);

        for( $i = 0; $i < $zip->numFiles; $i++ ) {
            $path = sprintf('%s/%s', $this->destination, $zip->getNameIndex($i));

            if (is_file($path)) {
                $this->files[] = new SplFileInfo($path);
            }
        }

        $zip->close();
        $this->cleanAndAddHeader();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove()
    {
        unlink($this->path);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Clean Crowdin translations and add a header
     */
    protected function cleanAndAddHeader()
    {
        if (true === $this->clean || null !== $this->header) {
            foreach ($this->files as $translation) {
                $formatter = FormatterFactory::getInstance($translation);

                if ($this->clean) {
                    $formatter->clean($translation);
                }

                if ($this->header) {
                    $formatter->addHeader($translation, $this->header);
                }
            }
        }
    }
}
