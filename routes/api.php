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
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ArticlePageController;
use App\Http\Controllers\FavouriteArticleController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\MailController;



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
Route::group(['prefix'=>'user','middleware'=>'assign:api'],function(){
    Route::post('/edit',[AuthController::class,'userEditProfile']);
    Route::get('/logout',[AuthController::class,'userLogout']);
    Route::get('/delete',[AuthController::class,'userDeleteAccount']);
    Route::get('/profile',[AuthController::class,'userGetProfile']);
});

Route::post('/admin/register',[AdminController::class,'adminRegister']);
Route::post('/admin/login',[AdminController::class,'adminLogin']);
Route::group(['prefix'=>'admin','middleware'=>'assign:admin_api'],function(){
    Route::post('/edit',[AdminController::class,'adminEditProfile']);
    Route::get('/logout',[AdminController::class,'adminLogout']);
    Route::get('/delete',[AdminController::class,'adminDeleteAccount']);
    Route::get('/profile',[AdminController::class,'adminGetProfile']);
});

Route::post('/artist/register',[ArtistController::class,'artistRegister']);
Route::post('/artist/login',[ArtistController::class,'artistLogin']);
Route::group(['prefix'=>'artist','middleware'=>'assign:artist_api'],function(){
    Route::post('/edit',[ArtistController::class,'artistEditProfile']);
    Route::get('/logout',[ArtistController::class,'artistLogout']);
    Route::get('/delete',[ArtistController::class,'artistDeleteAccount']);
    Route::get('/profile',[ArtistController::class,'artistGetProfile']);
});

Route::group(['prefix'=>'articles','middleware'=>'assign:artist_api'],function(){
    Route::post('/store',[ArticleController::class,'store']);   
    Route::post('/update/{article_id}',[ArticleController::class,'update']);
    Route::post('/delete/{article_id}',[ArticleController::class,'destroy']);
});

Route::group(['prefix'=>'articles/comments','middleware'=>'assign:api'],function(){
    Route::post('/store/{article_id}',[Article_CommentController::class,'store']);   
    Route::post('/update/{comment_id}',[Article_CommentController::class,'update']);
    Route::post('/delete/{comment_id}',[Article_CommentController::class,'destroy']);
});

Route::group(['prefix'=>'articles/comments','middleware'=>'assign:artist_api'],function(){
    Route::post('/storeFromArtist/{article_id}',[Article_CommentController::class,'storeFromArtist']);   
    Route::post('/updateFromArtist/{comment_id}',[Article_CommentController::class,'updateFromArtist']);
    Route::post('/deleteFromArtist/{comment_id}',[Article_CommentController::class,'destroyFromArtist']);
});

Route::group(['prefix'=>'articles/interactions','middleware'=>'assign:api'],function(){
    Route::post('/change/like/{article_id}',[Article_InteractionController::class,'changeLike']);   
    Route::post('/change/dislike/{article_id}',[Article_InteractionController::class,'changeDisLike']);
});

Route::group(['prefix'=>'articles/interactions','middleware'=>'assign:artist_api'],function(){
    Route::post('/changeFromArtist/like/{article_id}',[Article_InteractionController::class,'changeLikeFromArtist']);   
    Route::post('/changeFromArtist/dislike/{article_id}',[Article_InteractionController::class,'changeDisLikeFromArtist']);
});


Route::group(['prefix'=>'paintings','middleware'=>'assign:artist_api'],function(){
    Route::post('/store',[PaintingController::class,'store']);   
    Route::post('/update/{painting_id}',[PaintingController::class,'update']);
    Route::post('/delete/{painting_id}',[PaintingController::class,'destroy']);
});

Route::group(['prefix'=>'paintings/comments','middleware'=>'assign:api'],function(){
    Route::post('/store/{painting_id}',[Painting_CommentController::class,'store']);   
    Route::post('/update/{comment_id}',[Painting_CommentController::class,'update']);
    Route::post('/delete/{comment_id}',[Painting_CommentController::class,'destroy']);
});

Route::group(['prefix'=>'paintings/comments','middleware'=>'assign:artist_api'],function(){
    Route::post('/storeFromArtist/{painting_id}',[Painting_CommentController::class,'storeFromArtist']);   
    Route::post('/updateFromArtist/{comment_id}',[Painting_CommentController::class,'updateFromArtist']);
    Route::post('/deleteFromArtist/{comment_id}',[Painting_CommentController::class,'destroyFromArtist']);
});

Route::group(['prefix'=>'paintings/interactions','middleware'=>'assign:api'],function(){
    Route::post('/change/like/{painting_id}',[Painting_InteractionController::class,'changeLike']);   
    Route::post('/change/dislike/{painting_id}',[Painting_InteractionController::class,'changeDisLike']);
});

Route::group(['prefix'=>'paintings/interactions','middleware'=>'assign:artist_api'],function(){
    Route::post('/changeFromArtist/like/{painting_id}',[Painting_InteractionController::class,'changeLikeFromArtist']);   
    Route::post('/changeFromArtist/dislike/{painting_id}',[Painting_InteractionController::class,'changeDisLikeFromArtist']);
});

Route::group(['prefix'=>'painting/rate','middleware'=>'assign:api'],function(){
    Route::post('/change/{painting_id}',[Painting_EvaluationController::class,'changeRate']);   
});

Route::group(['prefix'=>'painting/rate','middleware'=>'assign:artist_api'],function(){
    Route::post('/changeFromArtist/{painting_id}',[Painting_EvaluationController::class,'changeRateFromArtist']);   
});

Route::group(['prefix'=>'artist/rate','middleware'=>'assign:api'],function(){
    Route::post('/change/{artist_id}',[Artist_EvaluationController::class,'changeRate']);   
});

Route::group(['prefix'=>'artist/rate','middleware'=>'assign:artist_api'],function(){
    Route::post('/changeFromArtist/{artist_id}',[Artist_EvaluationController::class,'changeRateFromArtist']);   
});

Route::group(['prefix'=>'favourite','middleware'=>'assign:api'],function(){
    Route::get('/show',[FavouriteController::class,'showFavouriteList']);   
    Route::post('/change/{painting_id}',[FavouriteController::class,'changeForPainting']);
    Route::post('/changeArticle/{article_id}',[FavouriteArticleController::class,'changeForArticle']);
});

Route::group(['prefix'=>'favourite','middleware'=>'assign:artist_api'],function(){
    Route::get('/showFromArtist',[FavouriteController::class,'showFavouriteListByArtist']);   
    Route::post('/changeFromArtist/{painting_id}',[FavouriteController::class,'changeForPaintingByArtist']);
    Route::post('/changeArticleFromArtist/{article_id}',[FavouriteArticleController::class,'changeForArticleByArtist']);
});

Route::group(['prefix'=>'home','middleware'=>'assign:api'],function(){
Route::get('/',[HomeController::class,'home']);
});
Route::group(['prefix'=>'home','middleware'=>'assign:artist_api'],function(){
Route::get('/artist',[HomeController::class,'homeForArtist']);
});

Route::group(['prefix'=>'articlesPage','middleware'=>'assign:api'],function(){
    Route::get('/',[ArticlePageController::class,'userArticlePage']);
    });
Route::group(['prefix'=>'articlesPage','middleware'=>'assign:artist_api'],function(){
    Route::get('/artist',[ArticlePageController::class,'artistArticlePage']);
});

Route::group(['prefix'=>'artist','middleware'=>'assign:api'],function(){
    Route::post('/change/follow/{artist_id}',[FollowController::class,'changeFollowing']);
});

Route::group(['prefix'=>'artist','middleware'=>'assign:artist_api'],function(){
    Route::post('/changeFromArtist/follow/{artist_id}',[FollowController::class,'changeFollowingFromArtist']);
});

Route::group(['prefix'=>'complaints','middleware'=>'assign:api'],function(){
    Route::post('/storeFromUserAgainstPainting/{painting_id}',[ComplaintController::class,'storeFromUserAgainstPainting']);
    Route::post('/storeFromUserAgainstArticle/{article_id}',[ComplaintController::class,'storeFromUserAgainstArticle']);
});

Route::group(['prefix'=>'complaints','middleware'=>'assign:artist_api'],function(){  
    Route::post('/storeFromArtistAgainstPainting/{painting_id}',[ComplaintController::class,'storeFromArtistAgainstPainting']);
    Route::post('/storeFromArtistAgainstArticle/{article_id}',[ComplaintController::class,'storeFromArtistAgainstArticle']);  
});

Route::group(['prefix'=>'certificates','middleware'=>'assign:artist_api'],function(){
    Route::post('/store',[ReliabilityCertificateController::class,'store']);
});

   
Route::group(['prefix'=>'archive','middleware'=>'assign:artist_api'],function(){
    Route::post('/store',[ArchiveController::class,'store']);
    Route::post('/editArchiveDetails/{archive_id}',[ArchiveController::class,'editArchiveDetails']);
    Route::post('/addPaintingToArchive/{archive_id}',[ArchiveController::class,'addPaintingToArchive']);
    Route::post('/removePaintingFromArchive/{archive_id}',[ArchiveController::class,'removePaintingFromArchive']);
    Route::post('/delete/{archive_id}',[ArchiveController::class,'destroy']);   
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

Route::group(['middleware'=>'assign:api'],function(){
    Route::post('/email_verification',[EmailVerificationController::class,'email_verification']);
    Route::get('/email_verification',[EmailVerificationController::class,'sendEmailVerification']);    
  
});

Route::get('send-mail', [MailController::class, 'index']);

Route::group(['prefix'=>'purchaseOrder','middleware'=>'assign:artist_api'],function(){
    Route::get('/show',[PurchaseOrderController::class,'showPurchaseOrders']);
    Route::post('/accept/{order_id}',[PurchaseOrderController::class,'acceptOrder']);
    Route::post('/reject/{order_id}',[PurchaseOrderController::class,'rejectOrder']);  
});

Route::group(['prefix'=>'purchaseOrder','middleware'=>'assign:api'],function(){
    Route::post('/changeOrder/{painting_id}',[PurchaseOrderController::class,'changeOrder']);
});




