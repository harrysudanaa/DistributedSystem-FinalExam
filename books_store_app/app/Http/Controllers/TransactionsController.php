<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    public function index()
    {
        return TransactionResource::collection(Transaction::get());
    }

    public function store(Request $request)
    {
        // var_dump($request->all()); 
        if (!Gate::any(['owner', 'staff'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'price' => 'required',
            'books_id' => 'required',
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 406);
        }
        $transaction = Transaction::create([
            'id' => $request->id,
            'status' => $request->status,
            'price' => $request->price,
            'books_id' => $request->books_id,
            'user_id' => $request->user_id,
        ]);
        return new TransactionResource($transaction);
    }
}
