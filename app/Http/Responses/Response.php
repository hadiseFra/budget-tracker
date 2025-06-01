<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class Response implements Responsable
{
    protected int $httpCode;
    protected mixed $data;
    protected string $message;

    /**
     * @param int $httpCode
     * @param mixed $data
     * @param string $message
     */
    public function __construct(int $httpCode, mixed $data = [], string $message = '')
    {
        $this->httpCode = $httpCode;
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function toResponse($request): \Illuminate\Http\JsonResponse
    {
        $payload = match (true) {
            $this->httpCode >= 400 => ['data' => null, 'message' => $this->message, 'status' => $this->httpCode],
            $this->httpCode >= 200 => ['data' => $this->data, 'message' => $this->message, 'status' => $this->httpCode],
        };

        return response()->json(
            data: $payload,
            status: $this->httpCode,
            options: JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param mixed $data
     * @return static
     */
    public static function ok(mixed $data)
    {
        return new static(httpCode: 200, data: $data, message: 'data is retrieved successfully.');
    }

    /**
     * @return static
     */
    public static function noContent()
    {
        return new static(httpCode: 204);
    }

    /**
     * @param mixed $data
     * @return static
     */
    public static function created(mixed $data)
    {
        return new  static(httpCode: 201, data: $data, message: 'data is created successfully.');
    }

    /**
     * @return static
     */
    public static function forbidden()
    {
        return new  static(httpCode: 403, message: 'You dont have access.');
    }

    /**
     * @return static
     */
    public static function notFound()
    {
        return new  static(httpCode: 404, message: 'Item not found.');
    }

    /**
     * @param string $message
     * @return static
     */
    public static function serverError(string $message = '')
    {
        return new static(httpCode: 500, message: $message ?? 'An error occurred.');
    }
}
