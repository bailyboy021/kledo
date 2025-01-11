<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Expense;
use App\Models\Approval;

class ExpenseController extends Controller
{
    /**
    *    @OA\Get(
    *       path="/expense/{id}",
    *       tags={"Expense"},
    *       operationId="getExpense",
    *       summary="Get Expense",
    *       description="Get Expense by ID",
    *       @OA\Parameter(
    *           name="id",
    *           in="path",
    *           required=true,
    *           @OA\Schema(
    *               type="integer"
    *           )
    *       ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "id": 2,
    *               "amount": 15000,
    *               "status": {
    *                   "id": 1,
    *                   "name": "menunggu persetujuan"
    *               },
    *               "approvals": {
    *                   {
    *                       "id": 4,
    *                       "approver": {
    *                           "id": 3,
    *                           "name": "Ina"
    *                       },
    *                       "status": {
    *                           "id": 2,
    *                           "name": "disetujui"
    *                       }
    *                   },
    *                   {
    *                       "id": 5,
    *                       "approver": {
    *                           "id": 1,
    *                           "name": "Ana"
    *                       },
    *                       "status": {
    *                           "id": 2,
    *                           "name": "disetujui"
    *                       }
    *                   },
    *                   {
    *                       "id": 6,
    *                       "approver": {
    *                           "id": 2,
    *                           "name": "Ani"
    *                       },
    *                       "status": {
    *                           "id": 1,
    *                           "name": "menunggu persetujuan"
    *                       }
    *                   }
    *               }
    *           }),
    *       ),
    *   )
    */
    public function getExpense(Request $request)
    {
        $request->merge(['id' => $request->id]);
        $validator = Validator::make($request->all(), [
            'id' => 'integer|exists:expenses,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $data = Expense::getExpense($validator->validated());
        return response()->json($data, 200);

        $validator = Validator::make($request->all(), [
            "id" => "required|int|exists:expenses,id",
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }
    }

    /**
    *    @OA\Post(
    *       path="/expense",
    *       tags={"Expense"},
    *       operationId="addExpense",
    *       summary="Add Expense",
    *       description="Create a new expense with a specified amount.",
    *       @OA\RequestBody(
    *           required=true,
    *           @OA\JsonContent(
    *               required={"amount"},
    *               @OA\Property(
    *                   property="amount",
    *                   type="integer",
    *                   description="Amount of the expense",
    *                   example=12000
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response="201",
    *           description="Expense created successfully",
    *           @OA\JsonContent(
    *               example={
    *                   "status": true,
    *                   "data": {
    *                       "id": 1,
    *                       "amount": 12000,
    *                       "status_id": 1,
    *                       "created_at": "2025-01-11T12:55:55.000000Z",
    *                       "updated_at": "2025-01-11T12:55:55.000000Z"
    *                   }
    *               }
    *           )
    *       ),
    *       @OA\Response(
    *           response="400",
    *           description="Invalid input data",
    *           @OA\JsonContent(
    *               example={
    *                   "error": {
    *                       "amount": {
    *                           "The amount field is required."
    *                       }
    *                   }
    *               }
    *           )
    *       )
    *   )
    */
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

    /**
    *    @OA\Patch(
    *       path="/expense/{id}/approve",
    *       tags={"Expense"},
    *       operationId="approveExpense",
    *       summary="Approve Expense",
    *       description="Approve an expense by an approver.",
    *       @OA\Parameter(
    *           name="id",
    *           in="path",
    *           required=true,
    *           description="ID of the expense to be approved",
    *           @OA\Schema(
    *               type="integer",
    *               example=10
    *           )
    *       ),
    *       @OA\RequestBody(
    *           required=true,
    *           @OA\JsonContent(
    *               required={"approver_id"},
    *               @OA\Property(
    *                   property="approver_id",
    *                   type="integer",
    *                   description="ID of the approver approving the expense",
    *                   example=3
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response="200",
    *           description="Expense approved successfully",
    *           @OA\JsonContent(
    *               example={
    *                   "message": "Approval successful",
    *                   "current_approval": {
    *                       "id": 28,
    *                       "expense_id": 10,
    *                       "approver_id": 3,
    *                       "status_id": 2,
    *                       "created_at": "2025-01-11T12:59:50.000000Z",
    *                       "updated_at": "2025-01-11T13:03:14.000000Z",
    *                       "deleted_at": null
    *                   },
    *                   "expense": {
    *                       "id": 10,
    *                       "amount": 12000,
    *                       "status_id": 1,
    *                       "created_at": "2025-01-11T12:59:50.000000Z",
    *                       "updated_at": "2025-01-11T12:59:50.000000Z"
    *                   }
    *               }
    *           )
    *       ),
    *       @OA\Response(
    *           response="400",
    *           description="Invalid input data",
    *           @OA\JsonContent(
    *               example={
    *                   "error": {
    *                       "id": {
    *                           "The id field must exist in expenses."
    *                       },
    *                       "approver_id": {
    *                           "The approver_id field is required."
    *                       }
    *                   }
    *               }
    *           )
    *       )
    *   )
    */
    public function approveExpense(Request $request)
    {
        $request->merge(['id' => $request->id]);
        $validator = Validator::make($request->all(), [
            "id" => "int|exists:expenses,id",
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
