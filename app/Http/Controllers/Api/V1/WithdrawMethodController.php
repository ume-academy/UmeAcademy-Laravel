<?php 
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawMethod\WithdrawMethodRequest;
use App\Http\Resources\WithdrawMethod\WithdrawMethodResource;
use App\Repositories\WithdrawMethodRepository;
use App\Services\WithdrawMethodService;
use Illuminate\Http\Request;
 
class WithdrawMethodController extends Controller {
    public function __construct(
       private WithdrawMethodService $withdrawMethodService,
    ){}

    public function addPaymentInfomation(WithdrawMethodRequest $request){
        try {
            $data = $request->all();
            $PaymentInfomation = $this->withdrawMethodService->addPaymentInfomation($data);
            return response()->json(
                ['message' => 'Thêm mới thông tin rút tiền thành công',
                'data' => new WithdrawMethodResource( $PaymentInfomation)            
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

