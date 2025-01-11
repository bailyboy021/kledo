<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ExpenseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Approvers
Route::post('/approvers', [ApproverController::class, 'addApprover']);

// Approval Stages
Route::post('/approval-stages', [ApprovalController::class, 'addApprovalStage']);
Route::put('/approval-stages/{id}', [ApprovalController::class, 'updateApprovalStage']);

// Expense
Route::get('/expense/{id}', [ExpenseController::class, 'getExpense']);
Route::post('/expense', [ExpenseController::class, 'addExpense']);
Route::patch('/expense/{id}/approve', [ExpenseController::class, 'approveExpense']);

