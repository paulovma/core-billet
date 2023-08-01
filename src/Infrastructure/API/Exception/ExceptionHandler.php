<?php

namespace App\Infrastructure\API\Exception;

use App\Domain\Exception\BusinessException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ExceptionHandler
{
    public static function handle(Exception $e)
    {
        if ($e instanceof BusinessException) {
            return self::handleBusiness($e);
        }

        return new JsonResponse(
            data:['message' => $e->getMessage(), 'code' => '00'],
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    private static function handleBusiness(BusinessException $exception)
    {
        if ($exception instanceof BusinessException) {
            return new JsonResponse(
                data:['message' => $exception->getMessage(), 'code' => $exception->getExceptionCode()],
                status: Response::HTTP_BAD_REQUEST
            );
        }
        
    }
}