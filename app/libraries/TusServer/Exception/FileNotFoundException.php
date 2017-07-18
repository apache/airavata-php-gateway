<?php

/**
 * Tus Server for Laravel 4.2
 * This file is part of the package.
 *
 * based on ZfTusServer:
 * (c) Jarosław Wasilewski <orajo@windowslive.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TusServer\Exception; 

/**
 * Exception class thrown when a file couldn't be found.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Christian Gärtner <christiangaertner.film@googlemail.com>
 */
class FileNotFoundException extends IOException
{
    public function __construct($message = null, $code = 0, \Exception $previous = null, $path = null)
    {
        if (null === $message) {
            if (null === $path) {
                $message = 'File could not be found.';
            } else {
                $message = sprintf('File "%s" could not be found.', $path);
            }
        }

        parent::__construct($message, $code, $previous, $path);
    }
}
