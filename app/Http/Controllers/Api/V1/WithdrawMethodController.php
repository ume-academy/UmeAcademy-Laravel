<?php 
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawMethod\WithdrawMethodRequest;
use App\Http\Resources\WithdrawMethod\WithdrawMethodResource;
use App\Http\Resources\WithdrawMethod\WithdrawRequestResource;
use App\Services\WithdrawMethodService;
use Illuminate\Http\Request;

class WithdrawMethodController extends Controller {
    public function __construct(
       private WithdrawMethodService $withdrawMethodService,
    ){}

    public function addWithdrawMethod(WithdrawMethodRequest $request){
        try {
            $data = $request->all();
            $method = $this->withdrawMethodService->addWithdrawMethod($data);
            
            return new WithdrawMethodResource($method);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    public function getWithdrawMethod() {
        try {
            $method = $this->withdrawMethodService->getWithdrawMethod();

            return new WithdrawMethodResource($method);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBanks() {
        try {
            $banks = $this->withdrawMethodService->getBanks();

            return response()->json(['data' => $banks]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateWithdrawMethod(WithdrawMethodRequest $request, $id) {
        try {
            $data = $request->all();
            $method = $this->withdrawMethodService->updateWithdrawMethod($id, $data);
            
            return new WithdrawMethodResource($method);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWithdrawRequest(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $requests = $this->withdrawMethodService->getWithdrawRequest($perPage);
            return WithdrawRequestResource::collection($requests);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $req, $id) {
        try {
            $status = $req->input('status');
            
            $request = $this->withdrawMethodService->updateStatus($id, $status);
            return new WithdrawRequestResource($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

