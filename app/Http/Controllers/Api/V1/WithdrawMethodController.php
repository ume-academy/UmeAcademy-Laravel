<?php 
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawMethod\WithdrawMethodRequest;
use App\Http\Resources\WithdrawMethod\WithdrawMethodResource;
use App\Http\Resources\WithdrawMethod\WithdrawRequestResource;
use App\Services\WithdrawMethodService;
use Illuminate\Database\QueryException;
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

        } catch (QueryException $e) {
            // Kiểm tra lỗi trùng lặp (1062)
            if ($e->getCode() === '23000') {
                return response()->json(['error' => 'Tài khoản đã tồn tại trên hệ thống.'], 400);
            }
    
            throw new $e;
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
            $startDate = $req->input('start_date');
            $endDate = $req->input('end_date');

            $requests = $this->withdrawMethodService->getWithdrawRequest($startDate, $endDate, $perPage);
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

