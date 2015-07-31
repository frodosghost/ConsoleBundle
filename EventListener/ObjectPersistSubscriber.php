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
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Manhattan\Bundle\ConsoleBundle\Entity\User;
use Manhattan\Bundle\ConsoleBundle\Entity\Publish;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ObjectPersistSubscriber implements EventSubscriber
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Publish) {
            if ($this->getSecurityToken() && $this->getSecurityToken()->getUser() instanceof User) {
                $entity->setCreatedBy($this->getSecurityToken()->getUser());
                $entity->setUpdatedBy($this->getSecurityToken()->getUser());
            }

            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $entity = $args->getEntity();

        if ($entity instanceof Publish) {
            if ($this->getSecurityToken() && $this->getSecurityToken()->getUser() instanceof User) {
                $user = $this->getSecurityToken()->getUser();
                $dateTime = new \DateTime();

                $oldUpdatedBy = $entity->getUpdatedBy();
                $oldUpdatedAt = $entity->getUpdatedAt();
                $entity->setUpdatedBy($user);
                $entity->setUpdatedAt($dateTime);

                $uow->propertyChanged($entity, 'updatedBy', $oldUpdatedBy, $user);
                $uow->propertyChanged($entity, 'updatedAt', $oldUpdatedAt, $dateTime);

                $uow->scheduleExtraUpdate($entity, array(
                    'updatedBy' => array($oldUpdatedBy, $user),
                    'updatedAt' => array($oldUpdatedAt, $dateTime)
                ));
            }
        }
    }

    /**
     * Lazy Loading of security context.
     * Returns TokenInterface
     *
     * @link(Circular Reference when injecting Security Context, http://stackoverflow.com/a/8713339/174148)
     * @return TokenInterface
     */
    private function getSecurityToken()
    {
        if ($this->container->get('security.context')->getToken() instanceof TokenInterface) {
            return $this->container->get('security.context')->getToken();
        }

        return null;
    }

}
