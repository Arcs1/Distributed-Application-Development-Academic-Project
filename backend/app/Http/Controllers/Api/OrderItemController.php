<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return OrderItemResource::collection(OrderItem::all());
    }

    public function orderItems($id)
    {
        return OrderItemResource::collection(OrderItem::where('order_id', $id)->get());
    }

    public function waitingItems()
    {
        OrderItemResource::$format = 'withTicketNum';
        OrderResource::$format = 'ticket_number';
        $collection = OrderItemResource::collection(OrderItem::where('status', 'W')->get());
        return $collection;
    }

    public function unpreparedItems()
    {
        OrderItemResource::$format = 'withTicketNum';
        OrderResource::$format = 'ticket_number';

        $user = User::where('id', Auth::id())->first();
        switch ($user->type) {
            case 'EC':
                return OrderItemResource::collection(OrderItem::where('status', 'P')->where('preparation_by', Auth::id())->get());
            case 'EM':
                return OrderItemResource::collection(OrderItem::where('status', 'P')->get());
        }
    }

    public function prepareItem(OrderItem $orderItem)
    {
        $orderItem->status = 'P';
        $orderItem->preparation_by = Auth::id();
        $orderItem->update();

        return new OrderItemResource($orderItem);
    }

    public function readyItem(OrderItem $orderItem)
    {
        $orderItem->status = 'R';
        $orderItem->update();

        // return OrderItemResource::collection(OrderItem::where('order_id', $orderItem->order_id)->whereIn('status', ['W', 'P'])->get());

        if (OrderItemResource::collection(OrderItem::where('order_id', $orderItem->order_id)->whereIn('status', ['W', 'P'])->get())->isEmpty()) {
            //no more order items to be prepared from the order
            $order = Order::where('id', $orderItem->order_id)->first();
            $order->status = 'R';
            $order->update();
            return $order;
        }

        return new OrderItemResource($orderItem);
    }

    public function store(Request $request)
    {

        $orderItem = new OrderItem();

        $orderItem->order_id = $request['order_id'];
        $orderItem->order_local_number = $request['order_local_number'];
        $orderItem->product_id = $request['product_id'];
        $orderItem->status = $request['status'];
        $orderItem->price = $request['price'];
        $orderItem->preparation_by = $request['preparation_by'];
        $orderItem->notes = $request['notes'];
        $orderItem->save();

        return $orderItem;
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();

        //if the only item, order passes to R
        if (OrderItemResource::collection(OrderItem::where('order_id', $orderItem->order_id)->where('status', 'W')->orWhere('status', 'P')->get())->isEmpty()) {
            //no more order items to be prepared from the order
            $order = Order::where('id', $orderItem->order_id)->first();
            $order->status = 'R';
            $order->update();
        }

        //if its the only item in the order, delete order as well
        if (OrderItemResource::collection(OrderItem::where('order_id', $orderItem->order_id)->get())->isEmpty()) {
            //no more order items to be prepared from the order
            $order = Order::where('id', $orderItem->order_id)->first();
            $order->delete();
        }

        return new OrderResource($orderItem);
    }


    public function mostFrequentCustomerItem($id){
        $orders = Order::where('customer_id',$id)->get();
        $orders11 = [];
        $numbers = [];
        $frequentItems=[];
        foreach($orders as $order){
            array_push($orders11,OrderItem::where('order_id',$order->id)->where('status','!=','C')->pluck('product_id'));
        }

        for($i = 0;$i<count($orders11);$i++){
            for($j=0;$j<count($orders11[$i]);$j++){
                array_push($numbers,$orders11[$i][$j]);
            }
        }

        $values = array_count_values($numbers);
        arsort($values);
        $frequent=array_slice(array_keys($values),0,3,true);

        for($i=0;$i<count($frequent);$i++){
            array_push($frequentItems,Product::where('id',$frequent[$i])->get());
        }

        return $frequentItems;
    }
}
