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

use Manhattan\Bundle\ConsoleBundle\Entity\User;

/**
 * Manhattan\Bundle\ConsoleBundle\Publish
 *
 * This abstract class is for easy addition of the publish functions with models
 */
abstract class Publish
{

    /**
     * @var Manhattan\Bundle\ConsoleBundle\Entity\User
     */
    protected $createdBy;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var Manhattan\Bundle\ConsoleBundle\Entity\User
     */
    protected $updatedBy;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * Set createdBy
     *
     * @param  Manhattan\Bundle\ConsoleBundle\Entity\User $createdBy
     * @return Publish
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return Manhattan\Bundle\ConsoleBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     * @return Post
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedBy
     *
     * @param  Manhattan\Bundle\ConsoleBundle\Entity\User $updatedBy
     * @return Publish
     */
    public function setUpdatedBy(User $updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return Manhattan\Bundle\ConsoleBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     * @return Post
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}
