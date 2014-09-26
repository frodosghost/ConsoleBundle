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

    const GOOGLE_URL = 'https://plus.google.com/%s/posts';

    const TWITTER_URL = 'http://twitter.com/%s';

    const LINKEDIN_URL = 'http://www.linkedin.com/in/%s/';

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
     * Return a string of the element
     * @return string
     */
    public function __toString()
    {
        $url = '';
        switch ($this->outlet) {
            case 'google-plus':
                $url = sprintf(self::GOOGLE_URL, $this->identifier);
                break;
            case 'twitter':
                $url = sprintf(self::TWITTER_URL, $this->identifier);
                break;
            case 'linkedin':
                $url = sprintf(self::LINKEDIN_URL, $this->identifier);
                break;
        }

        return $url;
    }

    /**
     * Returns Capitalised string of Provider
     *
     * @return string
     */
    public function getProvider()
    {
        $provider = '';
        switch ($this->outlet) {
            case 'google-plus':
                $provider = 'Google Plus';
                break;
            case 'twitter':
                $provider = 'Twitter';
                break;
            case 'linkedin':
                $provider = 'LinkedIn';
                break;
        }

        return $provider;
    }

    /**
     * Returns Boolean if the provider matches the requested item
     *
     * @param  string  $provider
     * @return boolean
     */
    public function hasProvider($provider)
    {
        return (!is_null($this->outlet) && $this->outlet == $provider) ? TRUE : FALSE;
    }

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
    public function setUser(User $user)
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
