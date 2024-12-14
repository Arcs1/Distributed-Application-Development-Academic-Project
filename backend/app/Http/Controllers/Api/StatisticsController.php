<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
class StatisticsController extends Controller
{
    public function ordersPorMes($year){

        $orders = Order::
        select(DB::raw('COUNT(id) as count'))
        ->whereYear('created_at',$year)
        ->where('status','D')
        ->groupBy(DB::raw("Month(created_at)"))
        ->pluck('count');

        $mesesOrders = Order::select(DB::raw("Month(created_at) as month"))
        ->whereYear('created_at',$year)
        ->groupBy(DB::raw("Month(created_at)"))
        ->pluck('month');

        $datasOrders= array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach($mesesOrders as $index => $mes){
        $datasOrders[$mes-1] = $orders[$index];
        }

        return $datasOrders;

    }


    public function faturacaoPorMes($year){
        $orders=Order::select(DB::raw("SUM(total_price) as sum"))
        ->whereYear('created_at',$year)
        ->groupBy(DB::raw("Month(created_at)"))
        ->pluck('sum');

        $mesesOrders = Order::select(DB::raw("Month(created_at) as month"))
        ->whereYear('created_at',$year)
        ->groupBy(DB::raw("Month(created_at)"))
        ->pluck('month');

        $datasOrders= array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach($mesesOrders as $index => $mes){
        $datasOrders[$mes-1] = $orders[$index];
        }

        return $datasOrders;
    }

    public function bestCustomer(){
        $teste1 = DB::table('orders')
        ->select(DB::raw('DISTINCT(customer_id)'),DB::raw('SUM(total_paid) as count'))
        ->groupBy('customer_id')
        ->get();

        $teste2=[];
        $counts =[];

        foreach($teste1 as $item){
            if($item->customer_id != null){
                array_push($teste2,$item);
                array_push($counts,$item->count);
            }
        }


        $maxCounts = max($counts);

        $cliente = [];
        foreach($teste2 as $item){
            if($item->count == $maxCounts){
                array_push($cliente,$item->customer_id);
            }
        }

        $userId = Customer::where('id',$cliente)->pluck('user_id');

        $user = User::where('id',$userId)->get()->toArray();

        array_push($user,$maxCounts);
        return $user;
    }

    public function mostRequestedItems(){
        $teste = DB::table('order_items')
        ->select('order_items.product_id')
        ->join('orders','orders.id','=','order_items.order_id')
        ->where('orders.status','D')
        ->pluck('order_items.product_id')->toArray();



        $values = array_count_values($teste);
        arsort($values);
        $frequent=array_slice(array_keys($values),0,5,true);

        $frequentItems = [];
        for($i=0;$i<count($frequent);$i++){
            array_push($frequentItems,Product::where('id',$frequent[$i])->get());
        }
        return $frequentItems;
    }

    public function totalOrders(){
        $totalOrders = Order::select(DB::raw("COUNT(id) as count"))
        ->where('status','D')
        ->pluck('count');

        return $totalOrders;

    }

    public function totalSales(){
        $totalSales = Order::select(DB::raw("SUM(total_paid) as sum"))
        ->pluck('sum');

        return $totalSales;
    }

    public function totalCancelledOrders(){
        $totalOrders = Order::select(DB::raw("COUNT(id) as count"))
        ->where('status','C')
        ->pluck('count');

        return $totalOrders;
    }

    public function customersPorMes($year){
        $customers= Customer::
        select(DB::raw('COUNT(id) as count'))
        ->whereYear('created_at',$year)
        ->groupBy(DB::raw("Month(created_at)"))
        ->pluck('count');

        $mesesCustomers = Customer::select(DB::raw("Month(created_at) as month"))
        ->whereYear('created_at',$year)
        ->groupBy(DB::raw("Month(created_at)"))
        ->pluck('month');

        $datasCustomers= array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach($mesesCustomers as $index => $mes){
        $datasCustomers[$mes-1] = $customers[$index];
        }

        return $datasCustomers;
    }




}
