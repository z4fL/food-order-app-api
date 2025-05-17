<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    public $status;
    public $message;
    public $resource;

    /**
     * OrderResource constructor.
     *
     * Initializes the resource with a status, message, and the resource data.
     *
     * @param mixed  $status   The status of the response (e.g., success, error).
     * @param string $message  A descriptive message for the response.
     * @param mixed  $resource The resource data to be transformed.
     */
    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => $this->resource
        ];
    }
}
