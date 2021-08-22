# symfony-security-bundle

## Setup

### Install via composer

``
composer install cooolinho/symfony-security-bundle
``

### update .env

    MAILER_DSN=smtp://user:pass@smtp.example.com:port

### update security.yml

#### add to encoders

    security:
        encoders:
            ...
            Cooolinho\Bundle\SecurityBundle\Entity\User:
                algorithm: auto

#### use in provider

    security:
        providers:
            ...
            my_custom_provider:
                entity:
                    class: Cooolinho\Bundle\SecurityBundle\Entity\User
                    property: email | username

#### update firewall

    security:
        firewalls:
            ...
            secured_admin_area:
                provider: my_custom_provider
                user_checker: Cooolinho\Bundle\SecurityBundle\Security\UserChecker
                custom_authenticator:
                    - Cooolinho\Bundle\SecurityBundle\Security\SecurityAuthenticator
                logout:
                    path: app_logout
                    target: app_login

#### add role hierarchy

    role_hierarchy:
        ROLE_SUPER_ADMIN: [ ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH ]
        ROLE_ADMIN: ROLE_USER

#### add access control

    access_control:
        - { path: ^/login, roles: PUBLIC_ACCESS }
        - { path: ^/logout, roles: PUBLIC_ACCESS }
        - { path: ^/admin, roles: ROLE_ADMIN }

### add cooolinho_security.yaml to config/packages

    cooolinho_security:
        route_after_login: # REQUIRED
        user_class: # REQUIRED
        registration_enabled: false # optional
        route_login: app_login # optional
        route_logout: app_logout # optional
        mailer_from: test@localhost # optional
        mailer_name: Localhost Mailbot # optional
        login_provider_property: email # optional

### ResetPassword Setup

First you have to create two classes: App\Entity\ResetPasswordRequest and App\Repository\ResetPasswordRequestRepository

#### App\Entity\ResetPasswordRequest

```php
<?php

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

/**
 * @ORM\Entity(repositoryClass=ResetPasswordRequestRepository::class)
 * @ORM\Table(name="users_reset_password_requests")
 */
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private object $user;

    public function __construct(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): object
    {
        return $this->user;
    }
}
```

#### App\Repository\ResetPasswordRequestRepository

```php
<?php

namespace App\Repository;

use App\Entity\ResetPasswordRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

/**
 * @method ResetPasswordRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResetPasswordRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResetPasswordRequest[]    findAll()
 * @method ResetPasswordRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResetPasswordRequestRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
    use ResetPasswordRequestRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordRequest::class);
    }

    public function createResetPasswordRequest(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken): ResetPasswordRequestInterface
    {
        return new ResetPasswordRequest($user, $expiresAt, $selector, $hashedToken);
    }
}
```

#### update reset_password.yaml in config/packages

    symfonycasts_reset_password:
        request_password_repository: App\Repository\ResetPasswordRequestRepository

### update config/routes/annotations.yaml

    cooolinho_security:
        resource: ../../vendor/cooolinho/symfony-security-bundle/src/Controller/
        type: annotation
