<?php

declare(strict_types=1);

namespace Cooolinho\Bundle\SecurityBundle\EventListener;

use Cooolinho\Bundle\SecurityBundle\Entity\UserInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private LoggerInterface $logger;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
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
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($encodedPassword);

        $this->logger->log(LogLevel::INFO, $user->getEmail() . ' password encrypted!');
    }
}
