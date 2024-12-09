<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WithdrawalRequest;
use App\Http\Resources\WithdrawMethod\WithdrawRequestResource;

class WebhookSepayController extends Controller
{
    public function autoUpdateStatusWithdrawRequest(Request $request)
    {
        try {
            $data = $request->all();
            // Lấy giá trị content
            $content = $data['content'];

            // cắt content theo dấu "-" để lấy ra phần code
            $parts = explode('-', $content);

            // Lấy phần code trong content
            $code = $parts[0];

            $q = WithdrawalRequest::where('code', $code)->first();
            $q->status = 1;
            $q->save();
            
            return WithdrawalRequest::where('code', $code)->first();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
        
    }
}
