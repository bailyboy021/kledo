<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Expense;
use App\Models\Approval;

class ExpenseController extends Controller
{
    public function getExpense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required|int|exists:expenses,id",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $data = Expense::getExpense($validator->validated());
        return response()->json($data, 200);
    }

    public function addExpense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "amount" => "required|int|min:1",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $data = Expense::addExpense($validator->validated());
        return response()->json($data, 201);
    }

    public function approveExpense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required|int|exists:expenses,id",
            "approver_id" => "required|int|exists:approvers,id",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $data = Expense::approveExpense($validator->validated());
        return response()->json($data, 200);
    }
}
