<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\OrderItem;

class OrderResource extends JsonResource
{
    public static $format = 'default';
    public function toArray($request)
    {
        switch (OrderResource::$format) {
            case 'withAllItems':
                return [
                    'id' => $this->id,
                    'ticket_number' => $this->ticket_number,
                    'status' => $this->status,
                    'customer_id' => $this->customer_id,
                    'orderItems' => OrderItemResource::collection(OrderItem::where('order_id', $this->id)->get()),
                    'total_price' => $this->total_price,
                    'total_paid' => $this->total_paid,
                    'total_paid_with_points' => $this->total_paid_with_points,
                    'points_gained' => $this->points_gained,
                    'points_used_to_pay' => $this->points_used_to_pay,
                    'payment_type' => $this->payment_type,
                    'payment_reference' => $this->payment_reference,
                    'date' => $this->date,
                    'delivered_by' => $this->delivered_by
                ];

            case 'withUnpreparedItems':
                return [
                    'id' => $this->id,
                    'ticket_number' => $this->ticket_number,
                    'status' => $this->status,
                    'customer_id' => $this->customer_id,
                    'orderItems' => OrderItemResource::collection(OrderItem::where('order_id', $this->id)->where('status', 'P')->get()),
                    'total_price' => $this->total_price,
                    'total_paid' => $this->total_paid,
                    'total_paid_with_points' => $this->total_paid_with_points,
                    'points_gained' => $this->points_gained,
                    'points_used_to_pay' => $this->points_used_to_pay,
                    'payment_type' => $this->payment_type,
                    'payment_reference' => $this->payment_reference,
                    'date' => $this->date,
                    'delivered_by' => $this->delivered_by
                ];

            case 'ticket_number':
                return [
                    'id' => $this->id,
                    'ticket_number' => $this->ticket_number
                ];

            default:
                return [
                    'id' => $this->id,
                    'ticket_number' => $this->ticket_number,
                    'status' => $this->status,
                    'customer_id' => $this->customer_id,
                    'total_price' => $this->total_price,
                    'total_paid' => $this->total_paid,
                    'total_paid_with_points' => $this->total_paid_with_points,
                    'points_gained' => $this->points_gained,
                    'points_used_to_pay' => $this->points_used_to_pay,
                    'payment_type' => $this->payment_type,
                    'payment_reference' => $this->payment_reference,
                    'date' => $this->date,
                    'delivered_by' => $this->delivered_by
                ];
        }
    }
}
