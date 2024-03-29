<?php

namespace Cooolinho\Bundle\SecurityBundle\Form;

use Cooolinho\Bundle\SecurityBundle\CooolinhoSecurityBundle;
use Cooolinho\Bundle\SecurityBundle\DependencyInjection\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginFormType extends AbstractType
{
    public const FORM_NAME = 'login_form';
    public const TOKEN_FIELD_NAME = '_csrf_token';
    public const TOKEN_ID = 'login_token';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['login_property'] === Configuration::LOGIN_PROVIDER_PROPERTY_EMAIL) {
            $builder->add($options['login_property'], EmailType::class, [
                'label' => 'security.user.' . $options['login_property'],
                'translation_domain' => CooolinhoSecurityBundle::TRANSLATION_DOMAIN,
                'data' => $options['lastUsername'],
            ]);
        } else {
            $builder->add($options['login_property'], TextType::class, [
                'label' => 'security.user.' . $options['login_property'],
                'translation_domain' => CooolinhoSecurityBundle::TRANSLATION_DOMAIN,
                'data' => $options['lastUsername'],
            ]);
        }

        $builder
            ->add('password', PasswordType::class, [
                'label' => 'security.login.password',
                'translation_domain' => CooolinhoSecurityBundle::TRANSLATION_DOMAIN,
            ])
            ->add('_remember_me', CheckboxType::class, [
                'label' => 'security.login.remember',
                'translation_domain' => CooolinhoSecurityBundle::TRANSLATION_DOMAIN,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'security.login.submit',
                'translation_domain' => CooolinhoSecurityBundle::TRANSLATION_DOMAIN,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'lastUsername' => '',
            'csrf_field_name' => self::TOKEN_FIELD_NAME,
            'csrf_token_id' => self::TOKEN_ID,
            'login_property' => 'email',
        ]);
    }

    public function getBlockPrefix(): string
    {
        return self::FORM_NAME;
    }
}
