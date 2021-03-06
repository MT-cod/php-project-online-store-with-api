<?php

namespace App\Http\RequestsProcessing\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

trait ApiResponses
{
    private function sendErrRespOnInvalidValidate(mixed $request): JsonResponse|null
    {
        if ($request->errors()) {
            return Response::json(['errors' => $request->errors()], 400);
        }
        return null;
    }

    private function sendResultRespAfterProcessing(array $result): JsonResponse
    {
        if (isset($result['errors'])) {
            $responseBody = (isset($result['data']))
                ? ['errors' => $result['errors'], 'data' => $result['data']]
                : ['errors' => $result['errors']];
            return Response::json($responseBody, $result['status']);
        }
        $responseBody = (isset($result['data']))
            ? ['success' => $result['success'], 'data' => $result['data']]
            : ['success' => $result['success']];
        return Response::json($responseBody, $result['status']);
    }
}
