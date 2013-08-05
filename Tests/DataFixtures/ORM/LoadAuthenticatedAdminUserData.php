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
        $user->setUsername('admin');
        $user->setEmail('admin@test.com');
        $user->setPlainPassword('test');
        $user->addRole('ROLE_SUPER_ADMIN');
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();

        $this->addReference('admin-user', $user);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }

}
