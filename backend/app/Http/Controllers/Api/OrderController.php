<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        OrderResource::$format = 'withAllItems';
        return OrderResource::collection(Order::where('status', 'W')->orWhere('status', 'P')->orWhere('status', 'R')->get());
    }

    public function readyOrders()
    {
        OrderResource::$format = 'default';
        $collection = OrderResource::collection(Order::where('status', 'R')->get());
        return $collection;
    }

    public function unpreparedOrders()
    {
        OrderResource::$format = 'withUnpreparedItems';
        $collection = OrderResource::collection(Order::where('status', 'P')->get());
        return $collection;
    }

    public function undeliveredOrders()
    {
        OrderResource::$format = 'withAllItems';
        $collection = OrderResource::collection(Order::where('status', 'R')->get());
        return $collection;
    }

    public function prepareOrder(Order $order)
    {
        $order->status = 'R';
        $order->update();
        return new OrderResource($order);
    }

    public function deliverOrder(Order $order)
    {
        $order->status = 'D';
        $order->delivered_by = Auth::id();
        $order->update();

        OrderResource::$format = 'default';
        return new OrderResource($order);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $currentUser = auth('api')->user();
            $id = $currentUser->id ?? null;

            if ($id != null) {
                $customer = Customer::where('user_id', $id)->first();
            }

            $latestOrderNumber = Order::orderBy('id', 'desc')->first()->ticket_number;
            if ($latestOrderNumber == 99) {
                $ticketNumber = 1;
            } else {
                $ticketNumber = $latestOrderNumber + 1;
            }

            $totalPaidWithPoints = $request['points_used_to_pay'] / 10 * 5;

            $order = new Order();

            $order->ticket_number = $ticketNumber;
            $order->status = 'P';
            $order->customer_id = $customer->id ?? null;
            $order->total_price = 0;
            $order->total_paid = 0;
            $order->total_paid_with_points = $totalPaidWithPoints;
            $order->points_gained = 0;
            $order->points_used_to_pay = $request['points_used_to_pay'];
            $order->payment_type = $request['payment_type'];
            $order->payment_reference = $request['payment_reference'];
            $order->date = date('Y-m-d');
            $order->delivered_by = null;

            $order->save();

            $total = 0;

            $hasHotDish = false;

            foreach ($request['products'] as $key => $productID) {

                $product = Product::where('id', $productID)->first();

                $total = $total + $product->price;

                if ($product->type == 'hot dish') {
                    $hasHotDish = true;
                }

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->order_local_number = $key + 1;
                $orderItem->product_id = $product->id;
                $orderItem->status = $product->type == 'hot dish' ? 'w' : 'r';
                $orderItem->price = $product->price;
                $orderItem->preparation_by = null;
                $orderItem->notes = null;

                $orderItem->save();
            }

            if (!$hasHotDish) {
                $order->status = 'R';
            }

            $order->total_price = $total;
            $order->total_paid = $total - $totalPaidWithPoints;


            if ($id != null) {
                $order->points_gained = intval($total / 10);
                $customer->points = ($customer->points - $order->points_used_to_pay) + intval($total / 10);

                $customer->save();
            }

            $order->save();

            DB::commit();

            $id = Order::max('id');
            return $id;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function destroy(Order $order)
    {
        OrderItem::where('order_id', $order->id)->get()->each(function ($item, $key) {
            $item->status = 'R';
            $item->update();
        });

        $order->status = 'C';
        $order->update();

        return $order;
    }

    public function userOrders($id)
    {
        $orders = Order::where('customer_id', $id)->orderBy('date', 'desc')->get();
        return $orders;
    }
}
