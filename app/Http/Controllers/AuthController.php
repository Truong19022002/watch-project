<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['login', 'register', 'me']]);//login, register methods won't go through the api guard
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('username', 'password');

        if (! $token = auth('user')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
    public function update(Request $request, $idUser)
    {
    $request->validate([
        'username' => 'required',
        'firstName' => 'required',
        'lastName' => 'required',
        'contact' => 'required',
        'password' => 'required',
        'email' => 'required',

    ]);

    try {
        $admin = JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    
    if ($admin->idUser != $idUser) {
        return response()->json(['message' => 'Unauthorized.'], 403);
    }

    $User = User::where('idUser', $idUser)->first();
    if (!$User) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $User->username = $request->input('username');
    $User->firstName = $request->input('firstName');
    $User->lastName = $request->input('lastName');
    $User->contact = $request->input('contact');
    $User->email = $request->input('email');
    $User->password = $request->input('password');

    $User->save();

    return response()->json(['message' => 'Client updated successfully', 'data' => $User]);
    }
    public function register(Request $request)
    {
        // Check if the email already exists
        $existingUser = User::where('email', $request->input('email'))->first();
        if ($existingUser) {
            return response()->json([
                'message' => 'Email already exists',
                'error' => 'duplicateEmail',
            ], 400);

            
        }
    
        // If the email doesn't exist, proceed with user registration
        $user = new User;
        $user->idUser = rand(1000000, 99999999);
        $user->username = $request->input('username');
        $user->firstName = $request->input('firstName');
        $user->lastName = $request->input('lastName');
        $user->email = $request->input('email');
        $user->contact = $request->input('contact');
        $user->maChucVu = $request->input('maChucVu');
        $user->password = Hash::make($request->input('password'));
    
        $user->save();
        
        $token = JWTAuth::fromUser($user);
    
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 200);
    }
    

    public function me()
    {
        return response()->json(auth('user')->user());
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
            'user' => auth('user')->user()
        ]);
    }
}
