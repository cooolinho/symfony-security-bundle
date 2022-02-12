<?php

namespace Cooolinho\Bundle\SecurityBundle\Controller;

use Cooolinho\Bundle\SecurityBundle\Form\ChangePasswordFormType;
use Cooolinho\Bundle\SecurityBundle\Form\ResetPasswordRequestFormType;
use Cooolinho\Bundle\SecurityBundle\Service\ConfigurationService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @Route("/reset-password")
 */
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private ResetPasswordHelperInterface $resetPasswordHelper;
    private ConfigurationService $parameterBag;

    public function __construct(
        ResetPasswordHelperInterface $resetPasswordHelper,
        ConfigurationService         $configuration
    )
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->configuration = $configuration;
    }

    /**
     * @Route("/", name="app_forgot_password_request")
     */
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer
            );
        }

        return $this->render('@CooolinhoSecurity/reset_password/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->getDoctrine()->getRepository($this->configuration->getUserClass())->findOneBy([
            'email' => $emailFormData,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('app_forgot_password_check_mail');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem handling your password reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('app_forgot_password_check_mail');
        }

        $email = (new TemplatedEmail())
            ->from(new Address($this->configuration->getMailerFrom(), $this->configuration->getMailerName()))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('@CooolinhoSecurity/reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
            ]);

        $mailer->send($email);

        // Marks that you are allowed to see the app_forgot_password_check_mail page.
        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('app_forgot_password_check_mail');
    }

    /**
     * @Route("/check-email", name="app_forgot_password_check_mail")
     */
    public function checkEmail(): Response
    {
        if (!$this->getTokenObjectFromSession()) {
            return $this->redirectToRoute('app_forgot_password_request');
        }

        return $this->render('@CooolinhoSecurity/reset_password/check_email.html.twig', [
            'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
        ]);
    }

    /**
     * @Route("/reset", name="app_forgot_password_reset")
     */
    public function reset(
        Request                      $request,
        UserPasswordEncoderInterface $passwordEncoder,
        string                       $token = null
    ): Response
    {
        if ($token = $request->get('token')) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_forgot_password_reset');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encode the plain password, and set it.
            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute($this->parameterBag->get($this->configuration->getRouteLogin()));
        }

        return $this->render('@CooolinhoSecurity/reset_password/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
