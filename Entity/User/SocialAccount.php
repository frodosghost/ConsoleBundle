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

use Manhattan\Bundle\ConsoleBundle\Entity\Publish;
use Manhattan\Bundle\ConsoleBundle\Entity\User;

/**
 * SocialAccount
 */
class SocialAccount extends Publish
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    private $outlet;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var Manhattan\Bundle\ConsoleBundle\Entity\User
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set outlet
     *
     * @param string $outlet
     * @return User
     */
    public function setOutlet($outlet)
    {
        $this->outlet = $outlet;

        return $this;
    }

    /**
     * Get outlet
     *
     * @return string
     */
    public function getOutlet()
    {
        return $this->outlet;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return User
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set user
     *
     * @param \EnviroRisk\ConsoleBundle\Entity\User $user
     * @return User
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \EnviroRisk\ConsoleBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

}
