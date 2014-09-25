<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Manhattan\Bundle\ConsoleBundle\Entity\Asset;

class AssetPersistSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::postPersist,
            Events::preUpdate,
            Events::postUpdate,
            Events::preRemove,
            Events::postRemove
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->preUpload();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->upload();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the preUpdate() function
        if ($entity instanceof Asset) {
            $entity->preUpdateAsset();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->replace();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->storeFilenameForRemove();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->removeUpload();
        }
    }

}
