<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{

    private \Doctrine\ORM\EntityManagerInterface $em;
    private \Doctrine\ORM\EntityRepository $repository;
    private  UserPasswordHasherInterface $hashPassword;
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_USER = 'USER';

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $this->em = $em;
        $this->hashPassword = $hasher;
        $this->repository = $this->em->getRepository(User::class);
    }

    public function getMessage(): string
    {
        $messages = [
            '200',
            'Done',
            'Ok',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }

    public function getById($id): User
    {
        return $this->repository->find($id);
    }

    public function getByParams($params): array
    {
        return $this->repository->findBy($params);
    }

    public function create(User $user): User
    {
        $user->setCreated(new \DateTime('now'))
            ->setUpdated(new \DateTime('now'))
            ->setRoles([UserService::ROLE_USER])
            ->setPassword($this->hashPassword($user, $user->getPassword()));

        $this->em->persist($user);
        $this->em->flush();
        $this->em->refresh($user);

        return $user;
    }

    public function update(User $user): User
    {
        $user->setUpdated(new \DateTime('now'));

        $this->em->persist($user);
        $this->em->flush();
        $this->em->refresh($user);

        return $user;
    }

    public function delete(User $user): User
    {
        $this->em->persist($user);
        $this->em->remove($user);
        $this->em->flush();
        $this->em->refresh($user);

        return $user;
    }
    public function hashPassword(User $user, $plainPassword)
    {
        return $this->hashPassword->hashPassword($user, $plainPassword);
    }

    public function getUserByEmail(string $email): User|null
    {
        return $this->repository->findOneBy(['email' => $email]);
    }
}