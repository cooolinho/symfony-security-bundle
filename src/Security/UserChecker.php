<?php

namespace Cooolinho\Bundle\SecurityBundle\Security;

use Cooolinho\Bundle\SecurityBundle\Entity\User;
use Cooolinho\Bundle\SecurityBundle\Exception\FalseLoginException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            throw new FalseLoginException('This account is not a admin-user');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            throw new FalseLoginException('This account is not a admin-user');
        }
    }
}
