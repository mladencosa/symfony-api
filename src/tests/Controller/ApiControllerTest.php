<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    // TODO Refresh database between tests

    public function testDeleteUserWithUserDetails(): void
    {
        $client = static::createClient();

        // Test user who has user details
        $userWithUserDetails = '/api/users/1/delete';
        $client->request('DELETE', $userWithUserDetails);
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testDeleteUserSuccessfully(): void
    {
        $client = static::createClient();

        // Test user without user details
        $userWithoutUserDetails = '/api/users/2/delete';
        $client->request('DELETE', $userWithoutUserDetails);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testDeleteUserNotFound(): void
    {
        $client = static::createClient();

        // Test user who does not exist
        $notExistingUser = '/api/users/25/delete';
        $client->request('DELETE', $notExistingUser);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
