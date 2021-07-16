# symfony-security-bundle

## Setup
### Install via composer
``
composer install cooolinho/symfony-security-bundle
``
### update security.yml 

#### add to encoders
    encoders:
        Cooolinho\Bundle\SecurityBundle\Entity\User:
            algorithm: auto

#### use in provider
        my_custom_provider:
            entity:
                class: Cooolinho\Bundle\SecurityBundle\Entity\User
                property: email
