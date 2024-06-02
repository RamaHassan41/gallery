<?php

namespace App\Http\Controllers;

use App\Models\Painting;
use App\Traits\GeneralTrait;
use App\Models\Purchase_Order;
use App\Models\Sold_Painting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PurchaseOrderController extends Controller
{
    use GeneralTrait;

    public function showPurchaseOrders(){
        $artist=Auth::guard('artist_api')->user();
        $paintingsWithPurchaseOrders = $artist->paintings()->get();

        if($paintingsWithPurchaseOrders->isEmpty()){
            return $this->sendResponse('No purchase orders exist to display', 200);
        }
        $purchaseOrdersWithUser=[];
        foreach($paintingsWithPurchaseOrders as $painting){
            if($painting->purchase_orders->isNotEmpty()) {
                foreach ($painting->purchase_orders as $order) {
                    //$user_info = $order->user;
                    $purchaseOrdersWithUser[] = [
                        'painting_info' => $painting,
                        'user_info' => $order->user,
                    ];
                }
            }
        }
    return $this->sendResponse($purchaseOrdersWithUser, 200);






        foreach($purchase_orders as $purchase_order){
            $createdAt=Carbon::parse($purchase_order->order_date);
            $timeAgo=$createdAt->diffForHumans();
            $purchase_order->formatted_creation_date=$timeAgo;
        }
        return $this->sendResponse([$purchase_orders,'Purchase orders are displayed successfully'],200);
    }

    public function changeOrder($painting_id){
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        $user=Auth::guard('api')->user();
        $purchase_order=$user->purchase_orders()->where('painting_id',$painting_id)->first();
        if($purchase_order){
            $purchase_order->delete();
            return $this->sendResponse('Purchase order is canceled successfully',200);
        }
        $purchase_order=$user->purchase_orders()->create([
            'painting_id'=>$painting->id,
            'order_date'=>now()->toDateTimeString(),
        ]);
        return $this->sendResponse('Purchase order is sended successfully',200);
    }

    public function acceptOrder($order_id){
        $order=Purchase_Order::find($order_id);
        if(!$order){
            return $this->sendError('Purchase order is not found',404);
        }
        $artist=Auth::guard('artist_api')->user();
        $painting=$order->painting;
        if($painting->artist_id!=$artist->id){
            return $this->sendError('Unauthorized',401);
        }
        $order->status='accepted';
        $order->save();
        $soldPainting=$artist->sold_paintings()->create([
            'painting_name'=>$painting->title,
            'price'=>$painting->price,
            'sell_date'=>now()->toDateTimeString(),
            'user_id'=>$order->user_id,
        ]);
        return $this->sendResponse([$order,'Purchase order is accepted successfully'],200);
    }

    public function rejectOrder($order_id){
        $order=Purchase_Order::find($order_id);
        if(!$order){
            return $this->sendError('Purchase order is not found',404);
        }
        $artist=Auth::guard('artist_api')->user();
        $painting=$order->painting;
        if($painting->artist_id!=$artist->id){
            return $this->sendError('Unauthorized',401);
        }
        $order->delete();
        return $this->sendResponse([$order,'Purchase order is rejected and deleted successfully'],200);
    }
}
