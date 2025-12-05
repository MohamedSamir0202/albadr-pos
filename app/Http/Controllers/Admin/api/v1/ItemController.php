<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ItemResource;
use App\Models\Item;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    use ApiResponse;
    public $resourceName = ItemResource::class;
    public function index()
    {
        $items = Item::paginate();
        return $this->paginatedResponseApi($items, "Items retrieved successfully");
    }

    public function show($id)
    {
        $item = Item::find($id);
        if ($item) {
            return $this->responseModelApi($item);
        } else {
            return $this->apiErrorMessage("Item not found", 404);
        }
    }
}
