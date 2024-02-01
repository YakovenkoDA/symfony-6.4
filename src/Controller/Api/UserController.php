<?php
namespace App\Controller\Api;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/user', name: 'api_user_')]
class UserController extends BaseController
{
    protected SerializerInterface $serializer;

    public function __construct(UserService $service, UserDTO $dto, SerializerInterface $serializer)
    {
        $this->service = $service;
        $this->dto = $dto;
        $this->serializer = $serializer;
    }

    private function setDTO(Request $request): static
    {
        $data = json_decode($request->getContent(), true);
        $this->dto->setId($data['id'] ?? null)
            ->setLastName($data['lastName'] ?? '')
            ->setFirstName($data['firstName'] ?? '')
            ->setPassword($data['password'] ?? '')
            ->setEmail($data['email'] ?? '');

        return $this;
    }

    #[Route('/{id}', name: 'view', methods: ['GET'])]
    public function view(User $user): JsonResponse
    {
        return $this->json($this->serializer->normalize($user));
    }

    #[Route('/', name: 'search', methods: ['GET'])]
    public function search(Request $request)
    {
        $params = $request->query->all();
        $result = $this->service->getByParams($params);

        return $this->json($this->serializer->normalize($result));
    }


    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $this->setDTO($request)
            ->validateDTO($validator, ['create']);

        $user = $this->container->get('dto.transformer')->DTOToObject($this->dto,  new User())
            ->setCreated(time())
            ->setUpdated(time());
        $user = $this->service->create($user);

        return $this->json($this->serializer->normalize($user));
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(User $entity, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $this->setDTO($request)
            ->validateDTO($validator);

        $user = $this->container->get('dto.transformer')->DTOToObject($this->dto, $entity)
            ->setUpdated(time());
        $user = $this->service->update($user);

        return $this->json([
                $this->service->getMessage(),
                $this->serializer->normalize($user)
            ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $user = $this->service->delete($user);

        return $this->json([
            $this->service->getMessage(),
            $this->serializer->normalize($user),
        ]);
    }
}