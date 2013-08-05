<?php

namespace Manhattan\Bundle\ConsoleBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Manhattan\Bundle\ConsoleBundle\Entity\User;

class LoadAuthenticatedAdminUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('super');
        $user->setEmail('super@test.com');
        $user->setPlainPassword('test');
        $user->addRole('ROLE_SUPER_ADMIN');
        $user->setEnabled(true);
        $user->setConfirmationToken('token123');

        $manager->persist($user);
        $this->addReference('user-super', $user);

        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@test.com');
        $user->setPlainPassword('test');
        $user->addRole('ROLE_ADMIN');
        $user->setEnabled(true);
        $user->setConfirmationToken('token1');

        $manager->persist($user);
        $this->addReference('user-admin', $user);

        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@test.com');
        $user->setPlainPassword('test');
        $user->addRole('ROLE_USER');
        $user->setEnabled(true);
        $user->setConfirmationToken('token-user');

        $manager->persist($user);
        $this->addReference('user-admin', $user);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }

}
