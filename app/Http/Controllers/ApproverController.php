<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Approver;

class ApproverController extends Controller
{
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
