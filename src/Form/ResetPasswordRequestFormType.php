<?php

namespace Cooolinho\SecurityBundle\Form;

use Cooolinho\SecurityBundle\CooolinhoSecurityBundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordRequestFormType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'security.user.email',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans(
                            'security.reset_password.message.not_blank',
                            [],
                            CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                        ),
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
