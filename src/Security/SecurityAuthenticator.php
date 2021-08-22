<?php

namespace Cooolinho\Bundle\SecurityBundle\Security;

use Cooolinho\Bundle\SecurityBundle\DependencyInjection\Configuration;
use Cooolinho\Bundle\SecurityBundle\DependencyInjection\CooolinhoSecurityExtension;
use Cooolinho\Bundle\SecurityBundle\Form\LoginFormType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class SecurityAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    private ParameterBagInterface $parameterBag;
    private UrlGeneratorInterface $urlGenerator;
    private string $loginProviderProperty;

    public function __construct(
        ParameterBagInterface $parameterBag,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->parameterBag = $parameterBag;
        $this->urlGenerator = $urlGenerator;

        $this->loginProviderProperty = $this->parameterBag->get(
            CooolinhoSecurityExtension::ALIAS . '.' . Configuration::LOGIN_PROVIDER_PROPERTY
        );

        if (!in_array($this->loginProviderProperty, [
            Configuration::DEFAULT_LOGIN_PROVIDER_PROPERTY, Configuration::LOGIN_PROVIDER_PROPERTY_USERNAME,
        ], true)) {
            $this->loginProviderProperty = Configuration::DEFAULT_LOGIN_PROVIDER_PROPERTY;
        }
    }

    public function supports(Request $request): bool
    {
        return $this->getLoginRoute() === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    protected function getLoginRoute(): string
    {
        return $this->parameterBag->get(CooolinhoSecurityExtension::ALIAS . '.' . Configuration::ROUTE_LOGIN);
    }

    public function authenticate(Request $request): PassportInterface
    {
        $form = $request->request->get(LoginFormType::FORM_NAME);

        $loginProperty = $form[$this->loginProviderProperty] ?? '';
        $password = $form['password'] ?? '';
        $token = $form[LoginFormType::TOKEN_FIELD_NAME] ?? '';

        $request->getSession()->set(Security::LAST_USERNAME, $loginProperty);

        return new Passport(
            new UserBadge($loginProperty),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge(LoginFormType::TOKEN_ID, $token),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate($this->getAfterLoginRoute()));
    }

    protected function getAfterLoginRoute(): string
    {
        return $this->parameterBag->get(CooolinhoSecurityExtension::ALIAS . '.' . Configuration::ROUTE_AFTER_LOGIN);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception->getMessage());

        return new RedirectResponse($this->urlGenerator->generate($this->getLoginRoute()));
    }
}
