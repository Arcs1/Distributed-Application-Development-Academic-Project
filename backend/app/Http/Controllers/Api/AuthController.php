<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Route;

const PASSPORT_SERVER_URL = "http://localhost";
const CLIENT_ID = 2;
const CLIENT_SECRET = "ElHCMEy7LMiwomVs4JuAuckJablWP9s5L36QG5ZH"

class AuthController extends Controller
{
    private function passportAuthenticationData($username, $password) {
        return [
        'grant_type' => 'password',
        'client_id' => CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'username' => $username,
        'password' => $password,
        'scope' => ''
        ];
        }




    public function login(Request $request){
        try{
            request()->request->add($this->passportAuthenticationData($request->email,$request->password));
            $request = Request::create(PASSPORT_SERVER_URL . '/oauth/token', 'POST');
            $response = Route::dispatch($request);
            $errorCode = $response->getStatusCode();
            $auth_server_response = json_decode((string) $response->content(), true);
            return response()->json($auth_server_response, $errorCode);
        }catch(\Exception $e){
            return response()->json('Authentication has failed', 401);
        }
    }

    public function logout(Request $request){
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
}
}
