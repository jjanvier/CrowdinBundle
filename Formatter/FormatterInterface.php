<?php

/*
 * This file is part of the Crowdin package.
 *
 * (c) Julien Janvier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jjanvier\Bundle\CrowdinBundle\Formatter;

use \SplFileInfo;

/**
 * Interface FormatterInterface
 *
 * Format a translation file to our needs
 */
interface FormatterInterface
{
    /**
     * Clean translations coming from Crowdin.
     *
     * @param SplFileInfo $file
     * @return mixed
     */
    public function clean(SplFileInfo $file);

    /**
     * Add a header to the translations coming from Crowdin.
     *
     * @param SplFileInfo $file
     * @param $header
     * @return mixed
     */
    public function addHeader(SplFileInfo $file, $header);
}
