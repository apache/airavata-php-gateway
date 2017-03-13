<?php

/**
 * Tus Server for Laravel 4.2
 * This file is part of the package.
 *
 * based on ZfTusServer: 
 * (c) Jaros³aw Wasilewski <orajo@windowlive.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TusServer\Exception; 

/**
 * Exception class thrown when a filesystem operation failure happens.
 *
 * @author Romain Neutron <imprec@gmail.com>
 * @author Christian Gärtner <christiangaertner.film@googlemail.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IOException extends \RuntimeException implements IOExceptionInterface
{
    private $path;

    public function __construct($message, $code = 0, \Exception $previous = null, $path = null)
    {
        $this->path = $path;

        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }
}
