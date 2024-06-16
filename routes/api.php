<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PaintingController;
use App\Http\Controllers\Article_CommentController;
use App\Http\Controllers\Painting_CommentController;
use App\Http\Controllers\Article_InteractionController;
use App\Http\Controllers\Painting_InteractionController;
use App\Http\Controllers\Painting_EvaluationController;
use App\Http\Controllers\Artist_EvaluationController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ReliabilityCertificateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ArticlePageController;
use App\Http\Controllers\FavouriteArticleController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SoldPaintingController;
use App\Http\Controllers\UserBlockController;
use App\Http\Controllers\AdminQueriesController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NotificationController;

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

Route::post('/user/register',[AuthController::class,'userRegister']);
Route::post('/user/login',[AuthController::class,'userLogin']);
Route::group(['middleware'=>['assign:api','activeUser:active','verifiedUser']],function(){
    Route::post('user/edit',[AuthController::class,'userEditProfile']);
    Route::get('user/logout',[AuthController::class,'userLogout']);
    Route::get('user/delete',[AuthController::class,'userDeleteAccount']);
    Route::get('user/profile',[AuthController::class,'userGetProfile']);
    Route::post('articles/comments/store/{article_id}',[Article_CommentController::class,'store']);   
    Route::post('articles/comments/update/{comment_id}',[Article_CommentController::class,'update']);
    Route::post('articles/comments/delete/{comment_id}',[Article_CommentController::class,'destroy']);
    Route::post('paintings/comments/store/{painting_id}',[Painting_CommentController::class,'store']);   
    Route::post('paintings/comments/update/{comment_id}',[Painting_CommentController::class,'update']);
    Route::post('paintings/comments/delete/{comment_id}',[Painting_CommentController::class,'destroy']);
    Route::post('articles/interactions/change/like/{article_id}',[Article_InteractionController::class,'changeLike']);   
    Route::post('articles/interactions/change/dislike/{article_id}',[Article_InteractionController::class,'changeDisLike']);
    Route::post('paintings/interactions/change/like/{painting_id}',[Painting_InteractionController::class,'changeLike']);   
    Route::post('paintings/interactions/change/dislike/{painting_id}',[Painting_InteractionController::class,'changeDisLike']);
    Route::post('painting/rate/change/{painting_id}',[Painting_EvaluationController::class,'changeRate']);   
    Route::post('artist/rate/change/{artist_id}',[Artist_EvaluationController::class,'changeRate']);   
    Route::post('artist/change/follow/{artist_id}',[FollowController::class,'changeFollowing']);
    Route::get('home/',[HomeController::class,'home']);
    Route::get('articlesPage/',[ArticlePageController::class,'userArticlePage']);
    Route::get('favourite/show',[FavouriteController::class,'showFavouriteList']);   
    Route::post('favourite/change/{painting_id}',[FavouriteController::class,'changeForPainting']);
    Route::post('favourite/changeArticle/{article_id}',[FavouriteArticleController::class,'changeForArticle']);
    Route::post('complaints/storeFromUserAgainstPainting/{painting_id}',[ComplaintController::class,'storeFromUserAgainstPainting']);
    Route::post('complaints/storeFromUserAgainstArticle/{article_id}',[ComplaintController::class,'storeFromUserAgainstArticle']);
    Route::post('purchaseOrder/change/{painting_id}',[PurchaseOrderController::class,'changeOrder']);
    Route::post('/forgot',[ForgotPasswordController::class,'forgotPassword']); 
    Route::post('/reset',[ForgotPasswordController::class,'resetPassword']);
});

// Route::post('/admin/login',[AdminController::class,'adminLogin']);
// Route::group(['middleware'=>['assign:admin_api','verifiedAdmin']],function(){
//     Route::post('admin/edit',[AdminController::class,'adminEditProfile']);
//     Route::get('admin/logout',[AdminController::class,'adminLogout']);
//     Route::get('admin/delete',[AdminController::class,'adminDeleteAccount']);
//     Route::get('admin/profile',[AdminController::class,'adminGetProfile']);
//     Route::get('certificate/showAll',[ReliabilityCertificateController::class,'showCertificates']);
//     Route::post('certificate/show/{id}',[ReliabilityCertificateController::class,'showCertificateDetails']);
//     Route::post('certificate/accept/{id}',[ReliabilityCertificateController::class,'acceptCertificate']);
//     Route::post('certificate/reject/{id}',[ReliabilityCertificateController::class,'rejectCertificate']);
//     Route::get('complaint/showAll',[ComplaintController::class,'showComplaints']);
//     Route::post('complaint/show/{id}',[ComplaintController::class,'showComplaintDetails']);
//     Route::post('complaint/accept/{id}',[ComplaintController::class,'acceptComplaint']);
//     Route::post('complaint/reject/{id}',[ComplaintController::class,'rejectComplaint']);
//     Route::post('/change_block_user/{user_id}',[UserBlockController::class,'changeBlockUser']);
//     Route::post('/change_block_artist/{artist_id}',[UserBlockController::class,'changeBlockArtist']);
//     Route::post('query/user',[AdminQueriesController::class,'queryAboutUser']);
//     Route::post('query/artist',[AdminQueriesController::class,'queryAboutArtist']);
//     Route::post('query/type',[AdminQueriesController::class,'queryAboutType']);
//     Route::post('query/painting',[AdminQueriesController::class,'queryAboutPainting']);
//     Route::post('query/article',[AdminQueriesController::class,'queryAboutArticle']);
//     Route::post('query/sold_painting',[AdminQueriesController::class,'queryAboutSoldPainting']);
//     Route::post('admin/forgot',[ForgotPasswordController::class,'adminForgotPassword']); 
//     Route::post('admin/reset',[ForgotPasswordController::class,'adminResetPassword']);
// });

Route::post('/artist/register',[ArtistController::class,'artistRegister']);
Route::post('/artist/login',[ArtistController::class,'artistLogin']);
Route::group(['middleware'=>['assign:artist_api',
'multiActiveArtist:activeAsUser|activeAsArtist','verifiedArtist']],function(){
    Route::post('artist/edit',[ArtistController::class,'artistEditProfile']);
    Route::get('artist/logout',[ArtistController::class,'artistLogout']);
    Route::get('artist/delete',[ArtistController::class,'artistDeleteAccount']);
    Route::get('artist/profile',[ArtistController::class,'artistGetProfile']);
    Route::post('articles/comments/storeFromArtist/{article_id}',[Article_CommentController::class,'storeFromArtist']);   
    Route::post('articles/comments/updateFromArtist/{comment_id}',[Article_CommentController::class,'updateFromArtist']);
    Route::post('articles/comments/deleteFromArtist/{comment_id}',[Article_CommentController::class,'destroyFromArtist']);
    Route::post('paintings/comments/storeFromArtist/{painting_id}',[Painting_CommentController::class,'storeFromArtist']);   
    Route::post('paintings/comments/updateFromArtist/{comment_id}',[Painting_CommentController::class,'updateFromArtist']);
    Route::post('paintings/comments/deleteFromArtist/{comment_id}',[Painting_CommentController::class,'destroyFromArtist']);
    Route::post('articles/interactions/changeFromArtist/like/{article_id}',[Article_InteractionController::class,'changeLikeFromArtist']);   
    Route::post('articles/interactions/changeFromArtist/dislike/{article_id}',[Article_InteractionController::class,'changeDisLikeFromArtist']);
    Route::post('paintings/interactions/changeFromArtist/like/{painting_id}',[Painting_InteractionController::class,'changeLikeFromArtist']);   
    Route::post('paintings/interactions/changeFromArtist/dislike/{painting_id}',[Painting_InteractionController::class,'changeDisLikeFromArtist']);
    Route::post('painting/rate/changeFromArtist/{painting_id}',[Painting_EvaluationController::class,'changeRateFromArtist']);   
    Route::post('artist/rate/changeFromArtist/{artist_id}',[Artist_EvaluationController::class,'changeRateFromArtist']);   
    Route::post('artist/changeFromArtist/follow/{artist_id}',[FollowController::class,'changeFollowingFromArtist']);
    Route::get('home/artist',[HomeController::class,'homeForArtist']);
    Route::get('articlesPage/artist',[ArticlePageController::class,'artistArticlePage']);
    Route::get('favourite/showFromArtist',[FavouriteController::class,'showFavouriteListByArtist']);   
    Route::post('favourite/changeFromArtist/{painting_id}',[FavouriteController::class,'changeForPaintingByArtist']);
    Route::post('favourite/changeArticleFromArtist/{article_id}',[FavouriteArticleController::class,'changeForArticleByArtist']);
    Route::post('complaints/storeFromArtistAgainstPainting/{painting_id}',[ComplaintController::class,'storeFromArtistAgainstPainting']);
    Route::post('complaints/storeFromArtistAgainstArticle/{article_id}',[ComplaintController::class,'storeFromArtistAgainstArticle']);
    Route::post('artist/forgot',[ForgotPasswordController::class,'artistForgotPassword']); 
    Route::post('artist/reset',[ForgotPasswordController::class,'artistResetPassword']); 
});

Route::group(['middleware'=>['assign:artist_api','activeArtist:activeAsArtist','verifiedArtist']],function(){
    Route::post('articles/store',[ArticleController::class,'store']);
    Route::post('articles/update/{article_id}',[ArticleController::class,'update']);
    Route::post('articles/delete/{article_id}',[ArticleController::class,'destroy']);
    Route::post('paintings/store',[PaintingController::class,'store']);   
    Route::post('paintings/update/{painting_id}',[PaintingController::class,'update']);
    Route::post('paintings/delete/{painting_id}',[PaintingController::class,'destroy']);
    Route::post('archive/store',[ArchiveController::class,'store']);
    Route::post('archive/editArchiveDetails/{archive_id}',[ArchiveController::class,'editArchiveDetails']);
    Route::post('archive/addPaintingToArchive/{archive_id}',[ArchiveController::class,'addPaintingToArchive']);
    Route::post('archive/removePaintingFromArchive/{archive_id}',[ArchiveController::class,'removePaintingFromArchive']);
    Route::post('archive/delete/{archive_id}',[ArchiveController::class,'destroy']);
    Route::get('purchaseOrder/show',[PurchaseOrderController::class,'showPurchaseOrders']);
    Route::post('purchaseOrder/accept/{order_id}',[PurchaseOrderController::class,'acceptOrder']);
    Route::post('purchaseOrder/reject/{order_id}',[PurchaseOrderController::class,'rejectOrder']);
});

Route::group(['middleware'=>['assign:artist_api','activeArtist:activeAsUser','verifiedArtist']],function(){
    Route::post('certificates/store',[ReliabilityCertificateController::class,'store']);
});

Route::group(['middleware'=>'multiGuard:api|artist_api'],function(){
    Route::get('/type/index',[TypeController::class,'index']);
    Route::post('/type/show/{type_id}',[TypeController::class,'show']);
    Route::post('/searchForArtist',[SearchController::class,'searchForArtist']);
    Route::post('/searchForPainting',[SearchController::class,'searchForPainting']);
    Route::post('/searchForType',[SearchController::class,'searchForType']);
    Route::get('archive/show/all',[ArchiveController::class,'index']);
    Route::post('showArchiveWorks/{archive_id}',[ArchiveController::class,'showArchiveWorks']);
    Route::post('showArtistArchives/{artist_id}',[ArchiveController::class,'showArtistArchives']);
    Route::post('/artist/show/followers/{artist_id}',[FollowController::class,'showArtistFollowers']);
    Route::get('/show/artists/list',[ArtistController::class,'showArtistsList']);
    Route::post('/show/artist/{artist_id}',[ArtistController::class,'showArtist']);
    Route::post('/articles/artist/show/{artist_id}',[ArticleController::class,'artistArticles']);
    Route::post('/articles/show/{article_id}',[ArticleController::class,'show']);
    Route::post('/articles/comments/show/{article_id}',[Article_CommentController::class,'showArticleComments']);
    Route::post('/articles/interactions/show/{article_id}',[Article_InteractionController::class,'showArticleInteractions']);
    Route::post('/paintings/artist/show/{artist_id}',[PaintingController::class,'artistPaintings']);
    Route::post('/paintings/show/{painting_id}',[PaintingController::class,'show']);
    Route::post('/paintings/comments/show/{painting_id}',[Painting_CommentController::class,'showPaintingComments']);
    Route::post('paintings/interactions/show/{painting_id}',[Painting_InteractionController::class,'showPaintingInteractions']);
    Route::post('/painting/rate/show/{painting_id}',[Painting_EvaluationController::class,'showPaintingRates']);
    Route::post('/artist/rate/show/{artist_id}',[Artist_EvaluationController::class,'showArtistRates']);
});

Route::group(['middleware'=>['assign:api','activeUser:active']],function(){
    Route::post('/verify',[EmailVerificationController::class,'verifyEmail']); 
});

Route::group(['prefix'=>'artist','middleware'=>['assign:artist_api',
'multiActiveArtist:activeAsUser|activeAsArtist']],function(){
    Route::post('/verify',[EmailVerificationController::class,'artistVerifyEmail']); 
});

// Route::group(['prefix'=>'admin','middleware'=>'assign:admin_api'],function(){
//     Route::post('/verify',[EmailVerificationController::class,'adminVerifyEmail']);
// });

Route::post('/note',[NotificationController::class,'index']);