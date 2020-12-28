<?php

namespace App\Http\Controllers\Api\Components;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * Class ErrorMessage
 * @package App\Http\Controllers\Api\Components
 *
 * @Schema(required={"message"})
 */
class ErrorMessage extends \RuntimeException implements Responsable
{
    /**
     * @Property()
     * @var string
     */
    protected $message;

    public function toResponse($request)
    {
        return new JsonResponse(['message' => $this->message]);
    }
}
