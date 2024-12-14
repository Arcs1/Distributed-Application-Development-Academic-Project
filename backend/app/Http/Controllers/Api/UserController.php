<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateFormRequest;
use App\Http\Requests\UserRegisterFormRequest;
use App\Http\Requests\UserUpdateFormRequest;
use App\Http\Requests\UserUpdatePasswordFormRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function update(Request $request,$id){
        $user = User::where('id',$id)->first();

          $user->name = $request->name;
          $user->type = $request->type;
          $user->email = $request->email;
          if ($request->hasFile('photo')) {
            Storage::delete('public/fotos/' . $user->photo_url);
            $path = $request->photo->store('public/fotos/');
            $user->photo_url = basename($path);
        }
        $user->save();

       return new UserResource($user);
    }

    public function find($id){
        return new UserResource(User::where('id',$id)->first());
    }

    public function index(){
        return UserResource::collection(User::paginate(10));
    }


    public function register(Request $request) {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        Customer::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'points'=>0,
            'nif'=>null,
            'default_payment_type'=>null,
            'default_payment_reference'=>null
        ]);
    }

    public function handleBlock(User $user){
        if($user->blocked == 1){
            $user->blocked = 0;
            $user->update();
        }else{
            $user->blocked = 1;
            $user->update();
        }
        return new UserResource($user);
    }

    public function destroy (User $user) {
        if($user->type == "C"){
            Customer::where('user_id',$user->id)->delete();
        }
        $user->delete();

        return new UserResource($user);
    }

    public function show_me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function store(Request $request, User $user) {

       $user->name = $request['name'];
       $user->email = $request['email'];
       $user->type = $request['type'];
       $user->password = Hash::make($request['password']);

       if ($request->hasFile('photo')) {
        $path = $request->photo->store('public/fotos/');
        $user->photo_url = basename($path);
    }
        $user->save();
}

    public function destroy_photo(User $user){
        Storage::delete('public/fotos/' . $user->photo_url);
        $user->photo_url = null;
        $user->save();
    }

    public function changePassword(Request $request,$id){
        $user = User::where('id',$id)->first();
        if(!Hash::check($request->oldPassword,$user->password)){
            $message = ([
                    $code=600,
                    $msg="Your current password does not matches with the password!"
            ]);
            return $message;
        }
        if($request->newPassword != $request->confirmNewPassword){
            $message = ([
                    $code=601,
                    $msg="New Passwords must match!"
            ]);
            return $message;
        }
        if($request->oldPassword == $request->newPassword){
            $message = ([
                $code=602,
                $msg="New Password must be different from the current!"
        ]);
        return $message;
        }

        $user->password = bcrypt($request->newPassword);
        $user->save();
        $message = ([
            $code=200,
            $msg="Password changed with success!"
    ]);
        return $message;
    }

}
