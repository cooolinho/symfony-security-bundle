<?php

namespace Cooolinho\SecurityBundle\DataFixtures;

use Cooolinho\SecurityBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 * @package Cooolinho\SecurityBundle\DataFixtures
 */
class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('coding@cooolinho.de');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'secret'));

        $manager->persist($user);

        $manager->flush();
    }
}
