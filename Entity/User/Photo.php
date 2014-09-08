<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Entity\User;

use Manhattan\Bundle\ConsoleBundle\Entity\Asset;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

/**
 * Photo
 */
class Photo extends Asset
{
    /**
     * @inheritDoc
     */
    public function getUploadDir()
    {
        return 'uploads/console/users';
    }

}
