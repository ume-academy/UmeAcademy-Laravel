<?php
namespace App\Http\Resources\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
// use App\Http\Resources\Category\SubCategoryResource;
// use App\Http\Resources\Category\SubCategoryCollection;
class CategoryResource extends JsonResource
{
    public function toArray($request)
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    return [
        'id' => $this->id,
        'name' => $this->name,
        'subcategory' => $this->parent_id
    ];
}

}
