<?php


namespace Cooolinho\Bundle\SecurityBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    public function addRole(string $role);

    public function setEmail(string $email);

    public function setPassword(string $password);
}
