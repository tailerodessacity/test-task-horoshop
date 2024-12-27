<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ApiResponseService
{
    public function __construct(private readonly SerializerInterface $serializer){}

    public function success($data = null, int $statusCode = JsonResponse::HTTP_OK, array $groups = []): JsonResponse
    {
        if ($groups && $data !== null) {
            $json = $this->serializer->serialize($data, 'json', ['groups' => $groups]);

            return new JsonResponse($json, $statusCode, [], true);
        }

        return new JsonResponse($data, $statusCode);
    }

    public function error(string $message, int $statusCode): JsonResponse
    {
        return new JsonResponse(['error' => ['message' => $message]], $statusCode);
    }
}
