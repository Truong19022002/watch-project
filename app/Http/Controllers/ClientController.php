<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Client;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    // 
    public function __construct()
    {
        $this->middleware('auth:client', ['except' => ['login', 'register', 'me']]);//login, register methods won't go through the api guard
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (! $token = auth('client')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $client = new Client;
        $client->maKhachHang = rand(10000000, 99999999);
        $client->tenKhachHang = $request->input('tenKhachHang');
        // $client->username = $request->input('username');
        $client->gioiTinh = $request->input('gioiTinh');
        $client->diaChi = $request->input('diaChi');
        $client->SDT = $request->input('SDT');
        $client->email = $request->input('email');
        $client->password = Hash::make($request->input('password'));

        
        $cart = new Cart;
        $cart->maGioHang = $client->maKhachHang;
        $cart->maKhachHang = $client->maKhachHang;
        $client->save();
        $cart->save();


        $token = JWTAuth::fromUser($client);

        return response()->json([
            'message' => 'User successfully registered',
            'token'=> $token,
            'user' => $client,
            'expires_in' => auth()->factory()->getTTL() * 60,
            'cart' => $cart
        ], 200);
    }

    public function me()
    {
        return response()->json(auth('client')->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60, //mention the guard name inside the auth fn
            'user' => auth('client')->user()
        ]);
    }
}
