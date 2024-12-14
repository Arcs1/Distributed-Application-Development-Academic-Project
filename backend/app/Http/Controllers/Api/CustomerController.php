<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(){
        return CustomerResource::collection(Customer::all());
    }

    public function destroy(Customer $customer){
        $customer->delete();
        $customer->users()->delete();
        return response()->json(null,204);
    }

    public function show_me(Request $request){
        return new CustomerResource(Customer::where('user_id',$request->user()->id)->first());
    }

    public function update(Request $request,$id){
        $customer = Customer::where('user_id',$id)->first();

          $customer->phone = $request->phone;
          $customer->nif = $request->nif;
          $customer->default_payment_type = $request->default_payment_type;
          $customer->default_payment_reference = $request->default_payment_reference;

        $customer->save();

       return new CustomerResource($customer);
    }
}
