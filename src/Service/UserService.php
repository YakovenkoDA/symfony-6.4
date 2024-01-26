<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{

    private \Doctrine\ORM\EntityManagerInterface $em;
    private \Doctrine\ORM\EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
        $this->em->persist($user);
        $this->em->flush();
        $this->em->refresh($user);

        return $user;
    }

    public function update(User $user): User
    {
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
}