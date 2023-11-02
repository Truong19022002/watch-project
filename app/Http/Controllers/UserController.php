<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:tkhachhang',
            'Email' => 'required|unique:tkhachhang',
            'Password' => 'required'
        ]);

        // $resultData = $request->all();
        // $result = User::create($resultData);
        // $result->save();
        // return $result;
        $maKhachHang = substr(uniqid(), 0, 8);

        $user = new User();

        $user->maKhachHang = substr(uniqid(), 0, 8);
        $user->tenKhachHang = $request->input('tenKhachHang');
        $user->username = $request->input('username');
        $user->gioiTinh = null;
        $user->diaChi = null;
        $user->SDT = null;
        $user->Email = $request->input('Email');
        $user->Password = $request->input('Password');
        $user->anhKH = null;
        $user->ghiChu = null;

        $user->save();
        $user = User::create([
            'maKhachHang' => $maKhachHang,
            'username' => $request->username,
            'Email' => $request->Email,
            'Password' => $request->Password,
            // 'Password' => Hash::make($request->Password),
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $email = $request->input('Email');
        $password = $request->input('Password');

        $user = User::where('Email', $email)->first();

        if ($user && $user->Password === $password) {
            // Xác thực thành công
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        } else {
            // Xác thực không thành công
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}
