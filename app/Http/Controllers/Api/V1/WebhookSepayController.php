<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WithdrawalRequest;
use App\Http\Resources\WithdrawMethod\WithdrawRequestResource;
use App\Models\TeacherWallet;

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
            $available_balance = TeacherWallet::where('teacher_id', $q->teacher_id)->pluck('available_balance')->first();
            $walletTeacher = TeacherWallet::where('teacher_id', $q->teacher_id)->first();
            $walletTeacher->available_balance = (int)$available_balance - (int)$q->money;
            $walletTeacher->save();
            $q->status = 1;
            $q->save();
            
            return WithdrawalRequest::where('code', $code)->first();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
        
    }
}
