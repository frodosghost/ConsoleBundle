<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Manhattan\Bundle\ConsoleBundle\Entity\User\SocialAccount;

/**
 * User
 */
class User extends BaseUser
{
    const ROLE_DEFAULT = 'ROLE_USER';

    const ROLE_ADMIN = 'ROLE_ADMIN';

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var Manhattan\Bundle\ConsoleBundle\Entity\User\SocialAccount
     */
    private $socialAccounts;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->socialAccounts = new ArrayCollection();

        parent::__construct();
    }

    /**
     * Get Gravatar
     *
     * @link(http://en.gravatar.com/site/implement/images/php/)
     *
     * @param  integer $size     Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param  string  $imageset Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param  string  $rating   Maximum rating (inclusive) [ g | pg | r | x ]
     * @param  boolean $secure   Determines if the secure URL is requried
     * @return string
     */
    function getGravatar($size = 80, $imageset = 'mm', $rating = 'g', $secure = false) {
        $http_url = 'http://www.gravatar.com/avatar/';
        $https_url = 'https://secure.gravatar.com/avatar/';

        $imageset = (in_array($imageset, array('404', 'mm', 'identicon', 'monsterid', 'wavatar'))) ? $imageset : 'mm';
        $rating = (in_array($rating, array('g', 'pg', 'r', 'x'))) ? $rating : 'g';

        $url = ($secure) ? $https_url : $http_url;
        $url .= md5(strtolower(trim($this->getEmail())));
        $url .= "?s=$size&d=$imageset&r=$rating";

        return $url;
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
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Add socialAccounts
     *
     * @param \Manhattan\Bundle\ConsoleBundle\Entity\User\SocialAccount $socialAccounts
     * @return User
     */
    public function addSocialAccount(SocialAccount $socialAccount)
    {
        if (!$this->socialAccounts->contains($socialAccount)) {
            $socialAccount->setUser($this);
            $this->socialAccounts->add($socialAccount);
        }

        return $this;
    }

    /**
     * Remove socialAccounts
     *
     * @param \Manhattan\Bundle\ConsoleBundle\Entity\User\SocialAccount $socialAccounts
     */
    public function removeSocialAccount(SocialAccount $socialAccount)
    {
        $this->socialAccounts->removeElement($socialAccount);
    }

    public function setSocialAccounts(Collection $socialAccounts)
    {
        $this->socialAccounts = $socialAccounts;

        return $this;
    }

    /**
     * Get socialAccounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSocialAccounts()
    {
        return $this->socialAccounts;
    }

    /**
     * Get socialAccount
     *
     * @param  string $accountType
     * @return Manhattan\Bundle\ConsoleBundle\Entity\User\SocialAccount|null
     */
    public function getSocialAccount($accountType)
    {
        $account = $this->socialAccounts->filter(function($element) use ($accountType) {
            return $element->hasProvider($accountType);
        });

        return ($account instanceof Collection) ? $account->first() : null;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * PrePersist
     */
    public function onCreate()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * PreUpdate
     */
    public function onUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }

}
