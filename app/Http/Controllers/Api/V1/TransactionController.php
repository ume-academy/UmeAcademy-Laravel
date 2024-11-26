<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $transactionService
    ){}

    public function getTransactionHistory(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $transactions = $this->transactionService->getTransactionHistory($perPage);
            return TransactionResource::collection($transactions);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
