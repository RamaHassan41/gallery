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
        $paintingsWithOrders=$artist->paintings()->has('purchase_orders')
        //->orderByDesc('purchase_orders.order_date')
        ->with('purchase_orders.user:id,name,image')->get();
        if($paintingsWithOrders->isEmpty()){
            return $this->sendResponse('No purchase orders exist to display', 200);
        }
        foreach($paintingsWithOrders as $purchase_order){
            foreach($purchase_order->purchase_orders as $order){
                $createdAt=Carbon::parse($order->order_date);
                $timeAgo=$createdAt->diffForHumans();
                $order->formatted_creation_date=$timeAgo;
            }
        }
        return $this->sendResponse([$paintingsWithOrders,'Purchase orders are displayed successfully'],200);
    }

    public function changeOrder($painting_id){
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        $user=Auth::guard('api')->user();
        $acceptedOrder=$painting->purchase_orders()->where('status','accepted')->first();
        if($acceptedOrder){
            return $this->sendError('An purchase order for this painting is already accepted', 400);
        }
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
        $acceptedOrder=$painting->purchase_orders()->where('status','accepted')->first();
        if($acceptedOrder){
            return $this->sendError('An purchase order for this painting is already accepted',400);
        }       
        $order->status='accepted';
        $order->save();
        $soldPainting=$painting->sold_paintings()->create([
            'sell_date'=>now()->toDateTimeString(),
            'user_id'=>$order->user_id,
        ]);
        $otherOrders=$painting->purchase_orders()->where('id','!=',$order_id)->get();
        foreach($otherOrders as $otherOrder){
            $otherOrder->delete();
        }
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
        if($order->status=='accepted'){
            return $this->sendError('This order is accepted',400);
        }
        $order->delete();
        return $this->sendResponse([$order,'Purchase order is rejected and deleted successfully'],200);
    }
}
