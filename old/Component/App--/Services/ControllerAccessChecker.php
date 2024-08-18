<?php

namespace Untek\Component\App\Services;

use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ControllerAccessChecker
{
    public function __construct(
        protected ContainerInterface $container,
        private TranslatorInterface $translator,
    )
    {
    }

    /**
     * Checks if the attribute is granted against the current authentication token and optionally supplied subject.
     *
     * @throws \LogicException
     */
    public function isGranted(mixed $attribute, mixed $subject = null): bool
    {
        if (!$this->container->has('security.authorization_checker')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        return $this->container->get('security.authorization_checker')->isGranted($attribute, $subject);
    }

    /**
     * Throws an exception unless the attribute is granted against the current authentication token and optionally
     * supplied subject.
     *
     * @throws AccessDeniedException
     */
    public function denyAccessUnlessGranted(mixed $attribute, mixed $subject = null, string $message = 'Access Denied.'): void
    {
        $this->denyAccessUnlessAuthenticated();
        if (!$this->isGranted($attribute, $subject)) {
            $exception = $this->createAccessDeniedException($message);
            $exception->setAttributes($attribute);
            $exception->setSubject($subject);
            throw $exception;
        }
    }

    public function denyAccessUnlessAuthenticated(): void
    {
        if ($this->getToken() == null || $this->getToken()->getUser() == null) {
            throw $this->createAuthenticationException();
        }
    }

    protected function createAuthenticationException(\Throwable $previous = null): AuthenticationException
    {
        $message = $this->translator->trans('unauthorizedMessage', [], 'user');
        $exception = new AuthenticationException($message, 0, $previous);
//        $exception->setToken($this->getToken());
        return $exception;
    }

    /**
     * Returns an AccessDeniedException.
     *
     * This will result in a 403 response code. Usage example:
     *
     *     throw $this->createAccessDeniedException('Unable to access this page!');
     *
     * @throws \LogicException If the Security component is not available
     */
    protected function createAccessDeniedException(string $message = 'Access Denied.', \Throwable $previous = null): AccessDeniedException
    {
        if (!class_exists(AccessDeniedException::class)) {
            throw new \LogicException('You cannot use the "createAccessDeniedException" method if the Security component is not available. Try running "composer require symfony/security-bundle".');
        }

        return new AccessDeniedException($message, $previous);
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @return UserInterface
     * @throws AuthenticationException
     *
     * @throws \LogicException If SecurityBundle is not available
     * @see TokenInterface::getUser()
     *
     */
    public function getUser(): UserInterface
    {
        $this->denyAccessUnlessAuthenticated();
        $token = $this->getToken();
        return $token->getUser();
    }

    public function getToken(): ?TokenInterface
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }
        $token = $this->container->get('security.token_storage')->getToken();

        return $token;
    }
}
