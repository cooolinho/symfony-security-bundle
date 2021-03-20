<?php

namespace Cooolinho\Bundle\SecurityBundle\DataFixtures;

use Cooolinho\Bundle\SecurityBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const DEMO_PASSWORD = 'secret';
    public const DEMO_HOST_EMAIL = '@example.com';
    public const DEMO_USERS = [
        'super-admin' => User::ROLE_SUPER_ADMIN,
        'admin' => User::ROLE_ADMIN,
        'user' => User::ROLE_USER,
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DEMO_USERS as $username => $role) {
            $user = new User();
            $user->setEmail($username . self::DEMO_HOST_EMAIL);
            $user->setPlainPassword(self::DEMO_PASSWORD);
            $user->addRole($role);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
