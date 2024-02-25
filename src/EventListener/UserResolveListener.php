<?php

namespace App\EventListener;

use App\Service\UserService;
use League\Bundle\OAuth2ServerBundle\Event\UserResolveEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserResolveListener
{
    protected UserService $service;
    protected UserPasswordHasherInterface $hashPassword;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserService $service, UserPasswordHasherInterface $hasher)
    {
        $this->service = $service;
        $this->hashPassword = $hasher;
    }

    /**
     * @param UserResolveEvent $event
     * @return void
     */
    public function onUserResolve(UserResolveEvent $event): void
    {
        try {
            $user = $this->service->getUserByEmail($event->getUsername());
        } catch (AuthenticationException $e) {
            return;
        }

        if (null === $user) {
            return;
        }

        if (!$this->hashPassword->isPasswordValid($user, $event->getPassword())) {
            return;
        }

        $event->setUser($user);
    }
}