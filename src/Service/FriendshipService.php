<?php

namespace App\Service;

use App\Entity\Friendship;
use Doctrine\ORM\EntityManagerInterface;

class FriendshipService
{
    private \Doctrine\ORM\EntityRepository $repository;
    const STATUS_SENT = 0;
    const STATUS_ACCEPT = 1;
    const STATUS_DECLINE = 2;

    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
        $this->repository = $this->em->getRepository(Friendship::class);
    }

    public function getByParams($params): array
    {
        return $this->repository->findBy($params);
    }

    public function create(Friendship $entity): Friendship
    {
        $entity->setStatus(self::STATUS_SENT)
            ->setCreated(new \DateTime('now'))
            ->setUpdated(new \DateTime('now'));

        $this->em->persist($entity);
        $this->em->flush();
        $this->em->refresh($entity);

        return $entity;
    }

    public function changeStatus(Friendship $entity, $status): Friendship
    {
        $entity->setUpdated(new \DateTime('now'))
            ->setAccepted(new \DateTime('now'))
            ->setStatus($status);

        $this->em->persist($entity);
        $this->em->flush();
        $this->em->refresh($entity);

        return $entity;
    }

    public function delete(Friendship $entity): Friendship
    {
        $this->em->persist($entity);
        $this->em->remove($entity);
        $this->em->flush();
        $this->em->refresh($entity);

        return $entity;
    }
}