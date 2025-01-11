<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ApprovalStage;

class ApprovalController extends Controller
{
    /**
    *    @OA\Post(
    *       path="/approval-stages",
    *       tags={"Approval Stage"},
    *       operationId="addApprovalStage",
    *       summary="Add Approval Stage",
    *       description="Create a new approval stage by assigning an approver.",
    *       @OA\RequestBody(
    *           required=true,
    *           @OA\JsonContent(
    *               required={"approver_id"},
    *               @OA\Property(
    *                   property="approver_id",
    *                   type="integer",
    *                   description="ID of the approver to be assigned to the approval stage",
    *                   example=3
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response="201",
    *           description="Approval stage created successfully",
    *           @OA\JsonContent(
    *               example={
    *                   "status": true,
    *                   "data": {
    *                             "id": 4,
    *                             "approver_id": 4,
    *                             "created_at": "2025-01-11T13:25:28.000000Z",
    *                             "updated_at": "2025-01-11T13:25:28.000000Z"
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
    *                       "approver_id": {
    *                           "The approver_id field is required.",
    *                           "The selected approver_id is invalid.",
    *                           "The approver_id has already been taken."
    *                       }
    *                   }
    *               }
    *           )
    *       )
    *   )
    */
    public function addApprovalStage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "approver_id" => [
                "required",
                "integer",
                "exists:approvers,id",
                "unique:approval_stages,approver_id"
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $data = ApprovalStage::addApprovalStage($validator->validated());
        return response()->json($data, 201);
    }

    /**
    *    @OA\Put(
    *       path="/approval-stages/{id}",
    *       tags={"Approval Stage"},
    *       operationId="updateApprovalStage",
    *       summary="Update Approval Stage",
    *       description="Update an existing approval stage by changing the approver assigned to it.",
    *       @OA\Parameter(
    *           name="id",
    *           in="path",
    *           required=true,
    *           description="ID of the approval stage to be updated",
    *           @OA\Schema(
    *               type="integer",
    *               example=5
    *           )
    *       ),
    *       @OA\RequestBody(
    *           required=true,
    *           @OA\JsonContent(
    *               required={"approver_id"},
    *               @OA\Property(
    *                   property="approver_id",
    *                   type="integer",
    *                   description="ID of the new approver to assign to this approval stage",
    *                   example=3
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response="200",
    *           description="Approval stage updated successfully",
    *           @OA\JsonContent(
    *               example={
    *                   "message": "Approval stage updated successfully",
    *                   "approval_stage": {
    *                       "id": 5,
    *                       "approver_id": 6,
    *                       "created_at": "2025-01-11T10:00:00.000000Z",
    *                       "updated_at": "2025-01-11T12:30:00.000000Z"
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
    *                           "The id field is required.",
    *                           "The selected id is invalid."
    *                       },
    *                       "approver_id": {
    *                           "The approver_id field is required.",
    *                           "The selected approver_id is invalid.",
    *                           "The approver_id has already been taken."
    *                       }
    *                   }
    *               }
    *           )
    *       )
    *   )
    */
    public function updateApprovalStage(Request $request)
    {
        $request->merge(['id' => $request->id]);
        $validator = Validator::make($request->all(), [
            "id" => "int|exists:approval_stages,id",
            "approver_id" => [
                "required",
                "int",
                "exists:approvers,id",
                Rule::unique('approval_stages', 'approver_id')->where(function ($query) use ($request) {
                    return $query->where('id', '!=', $request->id);
                })
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $data = ApprovalStage::updateApprovalStage($validator->validated());
        return response()->json($data, 200);
    }
}
