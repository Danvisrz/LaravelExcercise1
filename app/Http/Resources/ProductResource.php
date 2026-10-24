<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->product_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => (float) $this->price,
            'stock' => (int) $this->stock,
            'description' => $this->description,
            'image_url' => $this->img ? asset('storage/' . $this->img) : null,
            'category' => [
                'id' => $this->category_id,
                'name' => $this->category ? $this->category->category : null,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}