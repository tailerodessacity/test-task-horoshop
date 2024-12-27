<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserServiceInterface;
use App\Validation\DTOValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/v1/api/users', name: 'app_user_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly UserRepository $userRepository,
        private readonly DTOValidator $validator
    ){}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return $this->json($users, JsonResponse::HTTP_OK, [], ['groups' => 'user_list']);
    }

    #[Route('', name: 'create_user', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dto = new UserDTO(
            $data['login'],
            $data['phone'],
            $data['password'],
            $data['roles'] ?? ['ROLE_USER']
        );

        $this->validator->validate($dto);

        $user = $this->userService->createUser($dto);

        return $this->json($user, JsonResponse::HTTP_CREATED, [], ['groups' => 'user_detail']);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user_detail']);
    }

    #[Route('/users/{id}', name: 'update_user', methods: ['PUT'])]
    public function update(Request $request, User $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dto = new UserDTO(
            $data['login'],
            $data['phone'],
            $data['password'],
            $data['roles'] ?? $user->getRoles()
        );

        $this->validator->validate($dto);

        $updatedUser = $this->userService->updateUser($user, $dto);

        return $this->json($updatedUser, JsonResponse::HTTP_OK, [], ['groups' => 'user_detail']);

    }

    #[Route('/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);

        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
