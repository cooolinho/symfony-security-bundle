<?php

namespace Cooolinho\Bundle\SecurityBundle\DataFixtures;

use Cooolinho\Bundle\SecurityBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const DEMO_PASSWORD = 'secret';
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, static::DEMO_PASSWORD));

        $manager->persist($user);

        $manager->flush();
    }
}
