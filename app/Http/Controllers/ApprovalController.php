<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ApprovalStage;

class ApprovalController extends Controller
{
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

    public function updateApprovalStage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required|int|exists:approval_stages,id",
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
