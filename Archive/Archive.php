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
     * @var string filename of the archive
     */
    protected $filename;

    /**
     * @var string path where the archive will be extracted
     */
    protected $extractPath;

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
     * @param string $extractPath
     * @param bool   $clean
     * @param string $header
     */
    public function __construct($path, $extractPath, $clean = false, $header = null)
    {
        if (false === realpath($path)) {
            throw new \Exception(sprintf('Please provide a valid path instead of %s.', $path));
        }
        if (false === realpath($extractPath)) {
            throw new \Exception(sprintf('Please provide a valid path instead of %s.', $extractPath));
        }

        $this->files = array();
        $this->path = realpath($path);
        $this->extractPath = realpath($extractPath);
        $this->header = $header;
        $this->clean = $clean;
    }

    /**
     * {@inheritdoc}
     */
    public function extract()
    {
        $zip = new \ZipArchive();

        if (null === $this->filename) {
            throw new \Exception('Impossible to extract an archive without a filename.');
        }

        $archive = sprintf('%s/%s', $this->path, $this->filename);
        if (true !== $zip->open($archive)) {
            throw new \Exception(sprintf('Impossible to open the archive %s.', $archive));
        }

        $zip->extractTo($this->extractPath);

        for( $i = 0; $i < $zip->numFiles; $i++ ) {
            $path = sprintf('%s/%s', $this->extractPath, $zip->getNameIndex($i));

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
        if (null === $this->filename) {
            throw new \Exception('Impossible to remove an archive without a filename.');
        }

        unlink(sprintf('%s/%s', $this->path, $this->filename));

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
     * {@inheritdoc}
     */
    public function getExtractPath()
    {
        return $this->extractPath;
    }

    /**
     * {@inheritdoc}
     */
    public function setExtractPath($extractPath)
    {
        $this->extractPath = $extractPath;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilename()
    {
        return $this->filename;
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
