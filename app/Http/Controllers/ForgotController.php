<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotController extends Controller
{
    public function ForgotByEmail(Request $request){

        $email = $request->input('email');
        $people = DB::table('tkhachhang')
        ->where('email', $email)
        ->first();

        if(!$people){
            return response()->json(['message' => 'User not found'], 404);
        }
        
        $randomString = Str::random(9);
        $people->password = $randomString;

        $name = 'Test';
                Mail::send('Layout.email', compact('people'), function($email) use($people){
                    $email->subject('New Password');
                    $email->to($people->email);
                });
        $user = DB::table('tkhachhang')
        ->where('email', $email)
        ->update(['password' => Hash::make($randomString)]);  
        return response()->json(['message' => 'Email sent successfully']);
        // dd($people->password);
    }    
}

