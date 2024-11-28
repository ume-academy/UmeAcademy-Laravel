<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Level\LevelResource;
use App\Services\LevelService;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function __construct(
        private LevelService $levelService
    ){}

    public function getAllLevel() {
        try {
            $levels = $this->levelService->getAllLevel();
            
            return LevelResource::collection($levels);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
