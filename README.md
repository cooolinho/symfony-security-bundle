# symfony-security-bundle

## Setup
### Install via composer
``
composer install cooolinho/symfony-security-bundle
``
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
                    property: email

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

### add cooolinho_security.yaml to config/pacakges

    cooolinho_security:
        route_after_login: # REQUIRED

### update routes.yaml

    cooolinho_security:
        resource: '@CooolinhoSecurityBundle/Resources/config/routes.yaml'
