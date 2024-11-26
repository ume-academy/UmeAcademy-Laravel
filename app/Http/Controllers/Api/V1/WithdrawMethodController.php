<?php 
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawMethod\WithdrawMethodRequest;
use App\Http\Resources\WithdrawMethod\WithdrawMethodResource;
use App\Services\WithdrawMethodService;
 
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

}

