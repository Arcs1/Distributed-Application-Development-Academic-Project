<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\OrderResource;

class OrderItemResource extends JsonResource
{
    public static $format = 'default';
    public function toArray($request)
    {
        switch (OrderItemResource::$format) {

            case 'withTicketNum':
                return [
                    'id' => $this->id,
                    'ticket_number' => new OrderResource($this->order),
                    'product' => new ProductResource($this->product),
                    'order_id' => $this->order_id,
                    'order_local_number' => $this->order_local_number,
                    'product_id' => $this->product_id,
                    'status' => $this->status,
                    'price' => $this->price,
                    'preparation_by' => $this->preparation_by,
                    'notes' => $this->notes
                ];

            default:
                return [
                    'id' => $this->id,
                    'product' => new ProductResource($this->product),
                    'order_id' => $this->order_id,
                    'order_local_number' => $this->order_local_number,
                    'product_id' => $this->product_id,
                    'status' => $this->status,
                    'price' => $this->price,
                    'preparation_by' => $this->preparation_by,
                    'notes' => $this->notes
                ];
        }
    }
}
