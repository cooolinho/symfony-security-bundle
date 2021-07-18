<?php

declare(strict_types=1);

namespace Cooolinho\Bundle\SecurityBundle\EventListener;

use Cooolinho\Bundle\SecurityBundle\Entity\UserInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
    private UserPasswordHasherInterface $passwordEncoder;
    private LoggerInterface $logger;

    public function __construct(
        UserPasswordHasherInterface $passwordEncoder,
        LoggerInterface $logger
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->logger = $logger;
    }

    public function prePersist(UserInterface $user, LifecycleEventArgs $event): void
    {
        $this->hashUserPassword($user);
    }

    private function hashUserPassword(UserInterface $user): void
    {
        $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPlainPassword()));
        $this->logger->log(LogLevel::INFO, $user->getEmail() . ' password encrypted!');
    }
}
