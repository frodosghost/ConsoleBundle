<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Event;

/**
 * Configure menu events for building the console menu from other bundles
 *
 * @author James Rickard <james@frodosghost.com>
 */
final class MenuEvents
{
    const CONFIGURE = 'manhattan_console_bundle.menu_configure';
}
