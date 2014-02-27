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

interface FormatterInterface
{
    /**
     * Cleans translations coming from Crowdin.
     *
     * @param SplFileInfo $file
     * @return mixed
     */
    public function clean(SplFileInfo $file);

    /**
     * Adds a header to the translations coming from Crowdin.
     *
     * @param SplFileInfo $file
     * @param $header
     * @return mixed
     */
    public function addHeader(SplFileInfo $file, $header);
} 