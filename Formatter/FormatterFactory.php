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

use Symfony\Component\Finder\SplFileInfo;

class FormatterFactory
{
    public static function getInstance(SplFileInfo $file)
    {
        switch ($file->getExtension()) {
            case 'yml':
            case 'yaml':
                return new YamlFormatter();
            default:
                throw new \Exception('File with extension %s are not handled yet.', $file->getExtension());
        }
    }
} 