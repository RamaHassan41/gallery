<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReliabilityCertificateController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\UserBlockController;
use App\Http\Controllers\AdminQueriesController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\EmailVerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/admin/login',[AdminController::class,'adminLogin']);
Route::group(['middleware'=>['assign:admin_api','verifiedAdmin']],function(){
    Route::post('admin/edit',[AdminController::class,'adminEditProfile']);
    Route::get('admin/logout',[AdminController::class,'adminLogout']);
    Route::get('admin/delete',[AdminController::class,'adminDeleteAccount']);
    Route::get('admin/profile',[AdminController::class,'adminGetProfile']);
    Route::get('certificate/showAll',[ReliabilityCertificateController::class,'showCertificates']);
    Route::post('certificate/show/{id}',[ReliabilityCertificateController::class,'showCertificateDetails']);
    Route::post('certificate/accept/{id}',[ReliabilityCertificateController::class,'acceptCertificate']);
    Route::post('certificate/reject/{id}',[ReliabilityCertificateController::class,'rejectCertificate']);
    Route::get('complaint/showAll',[ComplaintController::class,'showComplaints']);
    Route::post('complaint/show/{id}',[ComplaintController::class,'showComplaintDetails']);
    Route::post('complaint/accept/{id}',[ComplaintController::class,'acceptComplaint']);
    Route::post('complaint/reject/{id}',[ComplaintController::class,'rejectComplaint']);
    Route::post('/change_block_user/{user_id}',[UserBlockController::class,'changeBlockUser']);
    Route::post('/change_block_artist/{artist_id}',[UserBlockController::class,'changeBlockArtist']);
    Route::post('query/user',[AdminQueriesController::class,'queryAboutUser']);
    Route::post('query/artist',[AdminQueriesController::class,'queryAboutArtist']);
    Route::post('query/type',[AdminQueriesController::class,'queryAboutType']);
    Route::post('query/painting',[AdminQueriesController::class,'queryAboutPainting']);
    Route::post('query/article',[AdminQueriesController::class,'queryAboutArticle']);
    Route::post('query/sold_painting',[AdminQueriesController::class,'queryAboutSoldPainting']);
    Route::post('admin/forgot',[ForgotPasswordController::class,'adminForgotPassword']); 
    Route::post('admin/reset',[ForgotPasswordController::class,'adminResetPassword']);
});

Route::group(['prefix'=>'admin','middleware'=>'assign:admin_api'],function(){
    Route::post('/verify',[EmailVerificationController::class,'adminVerifyEmail']);
});