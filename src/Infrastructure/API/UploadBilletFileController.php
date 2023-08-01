<?php

namespace App\Infrastructure\API;

use App\Application\File\CreateFileService;
use App\Infrastructure\API\Exception\ExceptionHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadBilletFileController extends AbstractController
{

    /**
     * @Route("/api/billets/file", name="upload_csv", methods={"POST"})
     */
    public function uploadCsv(Request $request, CreateFileService $createFileService): JsonResponse
    {
        try {
            $response = $createFileService->uploadCsvFile($request->files->get('csvFile'));

            return $this->json([
                'id' => $response->getId(),
                'status' => $response->getStatus(),
                'hash' => $response->getHash(),
            ], Response::HTTP_CREATED);
        } catch(Exception $e) {
            return ExceptionHandler::handle($e);
        }

        // return new JsonResponse(data: $normalizer->normalize($response), status: Response::HTTP_CREATED, json: true);
    }
}
