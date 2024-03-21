<?php

namespace App\Controller\Api;

use App\Entity\Friendship;
use App\Entity\User;
use App\Service\FriendshipService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/friendship', name: 'api_friendship_')]
class FriendshipController extends BaseController
{
    public function __construct(protected FriendshipService $service)
    {

    }

    #[Route('/{id}', name: 'view', methods: ['GET'])]
    public function view(Friendship $user): JsonResponse
    {
        return $this->json($user);
    }

    #[Route('/', name: 'request_list', methods: ['GET'])]
    public function list(Security $security): JsonResponse
    {
        $user = $security->getUser();
        $params = [
            'recipient' => $user,
            'status' => FriendshipService::STATUS_SENT,
        ];
        $result = $this->service->getByParams($params);

        return $this->json($result);
    }

    #[Route('/{id}', name: 'add', methods: ['POST'])]
    public function add(User $recipient): JsonResponse
    {
        $friendship = $this->service->create($recipient);

        return $this->json($friendship);
    }

    #[Route('/{id}/accept', name: 'accept', methods: ['PUT'])]
    public function accept(Friendship $entity): JsonResponse
    {
        $user = $this->getUser();
        if ($user->getId() != $entity->getRecipient()->getId()) {
            throw new AccessDeniedHttpException();
        }
        $friendship = $this->service->changeStatus($entity, FriendshipService::STATUS_ACCEPT);

        return $this->json($friendship);
    }

    #[Route('/{id}/decline', name: 'decline', methods: ['PUT'])]
    public function decline(Friendship $entity): JsonResponse
    {
        $user = $this->getUser();
        if ($user->getId() != $entity->getRecipient()->getId()) {
            throw new AccessDeniedHttpException();
        }

        $friendship = $this->service->changeStatus($entity, FriendshipService::STATUS_DECLINE);

        return $this->json($friendship);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Friendship $entity): JsonResponse
    {
        $user = $this->getUser();
        if ($user->getId() != $entity->getSender()->getId()) {
            throw new AccessDeniedHttpException();
        }
        $result = $this->service->delete($entity);
        return $this->json([$result]);
    }
}
