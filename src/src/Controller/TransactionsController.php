<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\TransactionsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller
 * @Route("/transactions", name="transactions_api")
 */
class TransactionsController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @Route("/", name="transactions", methods={"GET"})
     */
    public function getTransactions(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $source = $request->get('source');

        $data = new TransactionsService($entityManager);

        $json = [
          "source" => $source,
          "data" =>  $data->getList($source)
        ];

        return $this->json($json, Response::HTTP_OK, [], ['groups' => ['user-list']]);
    }
}
