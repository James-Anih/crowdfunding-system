<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function () {
    return response()->json([
        'success'=>true,
        'message'=> 'test'
    ]);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/campaign', [CampaignController::class, 'campaigns']);
Route::get('/user/campaign', [CampaignController::class, 'userCampaigns'])->middleware('auth:sanctum');


Route::group(['middleware'=>'auth:sanctum', 'prefix'=>'campaign'], function(){
    Route::post('/create', [CampaignController::class, 'create']);
    Route::put('/update/{campaignId}', [CampaignController::class, 'update']);
    Route::delete('/delete/{campaignId}', [CampaignController::class, 'deleteCampaign']);
    Route::get('/close', [CampaignController::class, 'closeCampaign']);
    Route::get('/donations/{campaignId}', [DonationController::class, 'campaignDonations']);
});

Route::group(['middleware'=>'auth:sanctum', 'prefix'=>'donation'], function(){
    Route::post('/', [DonationController::class, 'donate']);
    Route::get('/{donationId}', [DonationController::class, 'getDonation']);
});



