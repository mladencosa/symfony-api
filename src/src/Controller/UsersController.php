<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserDetails;
use App\Factory\UserDetailsFactory;
use App\Repository\CountryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller
 * @Route("/users", name="user_api")
 */
class UsersController extends AbstractController
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager
    ) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    /**
     * @param UserRepository $userRepository
     * @return JsonResponse
     * @Route("/", name="users", methods={"GET"})
     */
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        $data = $userRepository->getAllAustrianUsers();

        return $this->json($data, Response::HTTP_OK, [], ['groups' => ['user-list']]);
    }

    /**
     * @param UserRepository $userRepository
     * @param CountryRepository $countryRepository
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @Route("/{id}/edit_details", name="user_details_put",  methods={"PUT"})
     */
    public function updateUserDetails(
        UserRepository $userRepository,
        CountryRepository $countryRepository,
        int $id,
        Request $request
    ): JsonResponse {
        $user = $userRepository->find($id);

        if (!$user instanceof User) {
            $this->throwApiException(Response::HTTP_NOT_FOUND, "User does not exist");
        }

        if (!$user->getUserDetails() instanceof UserDetails) {
            $this->throwApiException(Response::HTTP_UNPROCESSABLE_ENTITY, "Unable to update user without user details");
        }

        $userDetailsFactory = new UserDetailsFactory($this->entityManager, $request);
        $user = $userDetailsFactory->updateUserDetails($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->logger->info("User with id $id updated successfully");

        $jsonBody = [
            'status' => Response::HTTP_OK,
            'message' => "User updated successfully",
        ];

        return $this->json($jsonBody);
    }

    /**
     * @param UserRepository $userRepository
     * @param int $id
     * @return JsonResponse
     * @Route("/{id}/delete", name="user_delete", methods={"DELETE"})
     */
    public function deleteUser(UserRepository $userRepository, int $id): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user instanceof User) {
            $this->throwApiException(Response::HTTP_NOT_FOUND, "User does not exist");
        }

        if ($user->getUserDetails() instanceof UserDetails) {
            $this->throwApiException(Response::HTTP_UNPROCESSABLE_ENTITY, "Unable to remove user with user details");
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->logger->info("User with id $id successfully removed");

        $jsonBody = [
            'status' => Response::HTTP_OK,
            'message' => "User successfully removed",
        ];

        return $this->json($jsonBody);
    }
}
