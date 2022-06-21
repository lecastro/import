<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\JsonResponse;
use App\Domain\Services\UploadService;
use App\Http\Requests\Uploads\UploadFileRequest;

class UploadController extends Controller
{
    protected UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function uploadTeams(UploadFileRequest $request): JsonResponse
    {
        try {
            $response['message'] = 'Arquivo enviado para processamento. Acompanhe seu andamento na tela de status de importação.';

            $this->uploadService->uploadTeams($request);

            return response()->json(
                $response,
                JsonResponse::HTTP_OK
            );
        } catch (Throwable $exception) {
            return response()->json(
                $exception->getMessage()
            );
        }
    }
}
