<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KdsOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->tracking_code ?? $this->id, // استخدام ORD-POMXZW أو المعرف
            'db_id' => $this->id,
            'type' => $this->delivery_type, // تحويل delivery_type إلى type
            'table_number' => $this->restaurant_table_id,
            'customer_name' => $this->customer_name,
            'driver_name' => $this->driver?->name,
            // تحويل حالة pending في لارافيل إلى new للسكربت
            'status' => $this->status === 'pending' ? 'new' : $this->status, 
            'created_at' => $this->created_at->toIso8601String(),
            'items' => $this->items->map(function ($item) {
                return [
                    'name' => $item->name, // أو $item->product_name حسب جدولك
                    'qty' => $item->quantity,
                    'notes' => $order->notes ?? '',
                ];
            }),
        ];
    }
    
}
