<?php

namespace Cooolinho\Bundle\SecurityBundle\Security;

use Cooolinho\Bundle\SecurityBundle\Entity\AbstractUser;
use Cooolinho\Bundle\SecurityBundle\Exception\FalseLoginException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    private const THIS_ACCOUNT_IS_NOT_A_USER = 'This account is not a user';

    /**
     * @throws FalseLoginException
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AbstractUser) {
            throw new FalseLoginException(self::THIS_ACCOUNT_IS_NOT_A_USER);
        }
    }

    /**
     * @throws FalseLoginException
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AbstractUser) {
            throw new FalseLoginException(self::THIS_ACCOUNT_IS_NOT_A_USER);
        }
    }
}
