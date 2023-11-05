<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function get(Request $request)
    {
        $result = DB::table('tloai')->get();

        return response()->json($result, 200);
    }
}
