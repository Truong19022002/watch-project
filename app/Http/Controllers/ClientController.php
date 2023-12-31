<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Client;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
    public function update(Request $request, $maKhachHang)
{
    $request->validate([
        'gioiTinh' => 'required',
        'diaChi' => 'required',
        'SDT' => 'required',
    ]);

    try {
        $user = JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
    if ($user->maKhachHang != $maKhachHang) {
        return response()->json(['message' => 'Unauthorized.'], 403);
    }
    $client = Client::where('maKhachHang', $maKhachHang)->first();
    if (!$client) {
        return response()->json(['message' => 'Client not found'], 404);
    }
    $client-> SDT = $request->input('SDT');
    $client->gioiTinh = $request->input('gioiTinh');
    $client->diaChi = $request->input('diaChi');
    $client->save();
    return response()->json(['message' => 'Client updated successfully', 'data' => $client]);
}

    public function editPassword(Request $request, $maKhachHang)
    {
    $request->validate([
        'matKhauCu' => 'required', 
        'matKhauMoi' => 'required|min:6', 
    ]);

    try {
        $user = JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
    if ($user->maKhachHang != $maKhachHang) {
        return response()->json(['message' => 'Unauthorized.'], 403);
    }
    $client = Client::where('maKhachHang', $maKhachHang)->first();
    if (!$client) {
        return response()->json(['message' => 'Client not found'], 404);
    }
    if (!Hash::check($request->input('matKhauCu'), $client->password)) {
        return response()->json(['message' => 'Incorrect old password'], 400);
    }
    $client->password =bcrypt($request->input('matKhauMoi'));

    $client->save();
    return response()->json(['message' => 'Client updated successfully', 'data' => $client]);
    }
    public function updatePassword(Request $request, $maKhachHang)
    {
    $request->validate([
        'password'=>'required',
    ]);

    try {
        $user = JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
    if ($user->maKhachHang != $maKhachHang) {
        return response()->json(['message' => 'Unauthorized.'], 403);
    }
    $client = Client::where('maKhachHang', $maKhachHang)->first();
    if (!$client) {
        return response()->json(['message' => 'Client not found'], 404);
    }
    $client->password = Hash::make($request->input('password'));
   
    $client->save();
    return response()->json(['message' => 'Client updated successfully', 'data' => $client]);
    }
    public function register(Request $request)
    {
        $existingUser = Client::where('email', $request->input('email'))->first();
        if ($existingUser) {
            return response()->json([
                'message' => 'Email already exists',
                'error' => 'duplicateEmail',
            ], 400);
        }
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
