<?php
namespace App\Controller\Api;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user', name: 'api_user_')]
class UserController extends AbstractController
{

    protected \App\Service\UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    #[Route('/number', name: 'number', methods: ['GET'])]
    public function number(Request $request): JsonResponse
    {
        $number = random_int(0, 100);

        return $this->json([
                               'message' => $this->service->getMessage(),
                               'number' => $number,
                               'attr' => $request->attributes,
                               'url' => $this->generateUrl('api_user_number'),
                           ]);
    }


    //todo: validations
    #[Route('/{id}', name: 'view', methods: ['GET'])]
    public function view(User $user): JsonResponse
    {
        return $this->json((array)$user);
    }

    #[Route('/', name: 'search', methods: ['GET'])]
    public function search(Request $request)
    {
        $params = $request->query->all();
        $result = $this->service->getByParams($params);
        dd($result);
    }


    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();

        $user->setLastName($data['lastName']);
        $user->setFirstName($data['firstName']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);
        $user->setCreated(time());
        $user->setUpdated(time());

        $user = $this->service->create($user);

        return $this->json((array)$user);
    }

    #[Route('/', name: 'update', methods: ['PUT'])]
    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->service->getById($data['id']);

        $user->setLastName($data['lastName'] ?? $user->getLastName());
        $user->setFirstName($data['firstName'] ?? $user->getFirstName());
        $user->setPassword($data['password'] ?? $user->getPassword());
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setUpdated(time());

        $user = $this->service->update($user);

        return $this->json([
            $this->service->getMessage(),
            (array)$user],
        );
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $user = $this->service->delete($user);

        return $this->json([
            $this->service->getMessage(),
            (array)$user,
        ]);
    }
}