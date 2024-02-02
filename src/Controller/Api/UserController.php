<?php
namespace App\Controller\Api;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/user', name: 'api_user_')]
class UserController extends BaseController
{
    protected SerializerInterface $serializer;

    public function __construct(UserService $service, SerializerInterface $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
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
    #[ParamConverter("dto",
        options: ["validator" => ["groups" => ['Create', 'Default']]],
        converter: "fos_rest.request_body")]
    public function create(UserDTO $dto, ConstraintViolationListInterface $errorList): JsonResponse
    {
        $this->handleValidationErrors($errorList);
        $user = $this->container->get('dto.transformer')->DTOToObject($dto,  new User())
            ->setCreated(time())
            ->setUpdated(time());

        $user = $this->service->create($user);

        return $this->json($this->serializer->normalize($user));
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[ParamConverter("dto", converter: "fos_rest.request_body")]
    public function update(User $entity, UserDTO $dto, ConstraintViolationListInterface $errorList): JsonResponse
    {
        $this->handleValidationErrors($errorList);

        $user = $this->container->get('dto.transformer')->DTOToObject($dto, $entity)
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