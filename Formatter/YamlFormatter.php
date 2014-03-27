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

class YamlFormatter implements FormatterInterface
{
    public function clean(SplFileInfo $file)
    {
        $array = file($file->getRealPath());

        if (count($array)) {
            // Crowdin adds --- on the beginning of every Yaml during the export.
            if (1 === preg_match("/^---/", $array[0])) {
                unset($array[0]);
            }

            file_put_contents($file->getRealPath(), $array);
        }
    }

    public function addHeader(SplFileInfo $file, $header)
    {
        $content = file_get_contents($file->getRealPath());
        $content = $header.$content;
        file_put_contents($file->getRealPath(), $content);
    }

} 