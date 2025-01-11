<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Approver;

class ApproverController extends Controller
{
    /**
    *    @OA\Post(
    *       path="/approvers",
    *       tags={"Approver"},
    *       operationId="addApprover",
    *       summary="Add Approver",
    *       description="Create a new approver with a unique name.",
    *       @OA\RequestBody(
    *           required=true,
    *           @OA\JsonContent(
    *               required={"name"},
    *               @OA\Property(
    *                   property="name",
    *                   type="string",
    *                   description="Name of the approver",
    *                   example="John Doe"
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response="201",
    *           description="Approver created successfully",
    *           @OA\JsonContent(
    *               example={
    *                   "id": 5,
    *                   "name": "John Doe",
    *                   "created_at": "2025-01-11T12:00:00.000000Z",
    *                   "updated_at": "2025-01-11T12:00:00.000000Z"
    *               }
    *           )
    *       ),
    *       @OA\Response(
    *           response="400",
    *           description="Invalid input data",
    *           @OA\JsonContent(
    *               example={
    *                   "error": {
    *                       "name": {
    *                           "The name field is required.",
    *                           "The name has already been taken."
    *                       }
    *                   }
    *               }
    *           )
    *       )
    *   )
    */
    public function addApprover(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|unique:approvers,name"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $data = Approver::addApprover($validator->validated());
        return response()->json($data, 201);
    }
}
