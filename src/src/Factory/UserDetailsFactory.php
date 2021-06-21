<?php

declare(strict_types=1);

namespace App\Factory;

use App\Contract\UserDetailsFactoryInterface;
use App\Entity\Country;
use App\Entity\User;
use App\Entity\UserDetails;
use App\Exception\ApiProblemException;
use App\Exception\ApiProblemModel;
use App\Validator\UserDetailsRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserDetailsFactory implements UserDetailsFactoryInterface
{
    private ObjectRepository $countryRepository;
    private Request $request;

    public function __construct(EntityManagerInterface $entityManager, Request $request)
    {
        $this->countryRepository = $entityManager->getRepository(Country::class);
        $this->request = $request;
    }

    /**
     * @param User $user
     * @return User
     */
    public function updateUserDetails(User $user): User
    {
        // Validate request data and throw an error if failed
        UserDetailsRequestValidator::validate($this->request);

        // TODO: I know shity approach, we should create a custom normalizer and denormalzier or DTO for such operations

        if (!$user instanceof User) {
            static::throwApiException(Response::HTTP_NOT_FOUND, "User not found");
        }

        if (!$user->getUserDetails() instanceof UserDetails) {
            static::throwApiException(Response::HTTP_UNPROCESSABLE_ENTITY, "User does not have user details");
        }

        if ($this->request->get('firstName')) {
            $user->getUserDetails()->setFirstName($this->request->get('firstName'));
        }

        if ($this->request->get('lastName')) {
            $user->getUserDetails()->setLastName($this->request->get('lastName'));
        }

        if ($this->request->get('phoneNumber')) {
            $user->getUserDetails()->setPhoneNumber($this->request->get('phoneNumber'));
        }

        // If citizenshipIso provided, check is such country exists and then assign
        if ($this->request->get('citizenshipIso')) {
            $countryIso = (string)$this->request->get('citizenshipIso');

            $newCountry = $this->countryRepository->findByIso($countryIso);

            if (!$newCountry instanceof Country) {
                static::throwApiException(Response::HTTP_NOT_FOUND, "Country with iso $countryIso does not exist");
            }

            $user->getUserDetails()->setCountry($newCountry);
        }

        return $user;
    }

    private static function throwApiException(int $status, string $message = ''): void
    {
        $apiProblem = new ApiProblemModel($status, $message);
        throw new ApiProblemException($apiProblem);
    }
}
