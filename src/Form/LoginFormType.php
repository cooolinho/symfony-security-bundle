<?php

namespace Cooolinho\Bundle\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginFormType extends AbstractType
{
    public const FORM_NAME = 'login_form';
    public const TOKEN_FIELD_NAME = '_csrf_token';
    public const TOKEN_ID = 'login_token';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'data' => $options['lastUsername'],
            ])
            ->add('password', PasswordType::class)
            ->add('_remember_me', CheckboxType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sign In',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'lastUsername' => '',
            'csrf_field_name' => self::TOKEN_FIELD_NAME,
            'csrf_token_id' => self::TOKEN_ID,
        ]);
    }

    public function getBlockPrefix()
    {
        return self::FORM_NAME;
    }
}
