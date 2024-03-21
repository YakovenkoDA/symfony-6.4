<?php
namespace App\Controller\Api;

use App\DTO\ChangePasswordDTO;
use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/user', name: 'api_user_')]
class UserController extends BaseController
{
    public function __construct( protected UserService $service)
    {

    }

    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function profile(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json($user);
    }

    #[Route('/friends', name: 'friend_list', methods: ['GET'])]
    public function friendList(): JsonResponse
    {
        $user = $this->getUser();
        $result = $this->service->getFriendList($user);

        return $this->json($result);
    }

    #[Route('/', name: 'search', methods: ['GET'])]
    public function search(Request $request)
    {
        $params = $request->query->all();
        $result = $this->service->getByParams($params);

        return $this->json($result);
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    #[ParamConverter("dto",
        options: ["validator" => ["groups" => ['Create', 'Default']]],
        converter: "fos_rest.request_body")]
    public function create(UserDTO $dto, ConstraintViolationListInterface $errorList): JsonResponse
    {
        $this->handleValidationErrors($errorList);
        $user = $this->container->get('dto.transformer')->DTOToObject($dto, new User());
        $user = $this->service->create($user);

        return $this->json($user);
    }

    #[Route('/{id}', name: 'view', methods: ['GET'])]
    public function view(User $user): JsonResponse
    {
        return $this->json($user);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[ParamConverter("dto", converter: "fos_rest.request_body")]
    public function update(User $entity, UserDTO $dto, ConstraintViolationListInterface $errorList): JsonResponse
    {
        $this->handleValidationErrors($errorList);
        $user = $this->container->get('dto.transformer')->DTOToObject($dto, $entity);
        $user = $this->service->update($user);

        return $this->json([
                $this->service->getMessage(),
                $user
            ]);
    }

    #[Route('/{id}/changePassword', name: 'changePassword', methods: ['PUT'])]
    #[ParamConverter("dto", converter: "fos_rest.request_body")]
    public function changePassword(User $entity, ChangePasswordDTO $dto, ConstraintViolationListInterface $errorList): JsonResponse
    {
        $this->handleValidationErrors($errorList);
        $entity->setPassword($this->service->hashPassword($entity, $dto->getPassword()));

        $user = $this->service->update($entity);

        return $this->json([
                $this->service->getMessage(),
                $user
            ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $user = $this->service->delete($user);

        return $this->json([
            $this->service->getMessage(),
            $user,
        ]);
    }
}