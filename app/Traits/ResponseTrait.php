<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     * Standard success response.
     * 
     * @param mixed $resource    Data to be returned.
     * @param string $message Optional success message.
     * @param int $status HTTP status code.
     * @return JsonResponse
     */
    protected function success($resource, $message = 'Request successful.', $status = JsonResponse::HTTP_OK)
    {
        return $resource->additional([
            'status' => 'success',
            'message' => $message,
        ], $status);
    }

    /**
     * Response for resource created.
     * 
     * @param mixed $data Data of the newly created resource.
     * @param string $message Success message.
     * @return JsonResponse
     */
    protected function created($data, $message = 'Resource created successfully.')
    {
        return $this->success($data, $message, JsonResponse::HTTP_CREATED);
    }

    /**
     * Response for resource updated.
     * 
     * @param mixed $data Data of the newly created resource.
     * @param string $message Success message.
     * @return JsonResponse
     */
    protected function updated($data, $message = 'Resource updated successfully.')
    {
        return $this->success($data, $message);
    }

    /**
     * Response with no content.
     * 
     * @return JsonResponse
     */
    protected function noContent()
    {
        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
