<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SizeController extends Controller
{
    public function get(Request $request)
    {
        $result = DB::table('tkichthuoc')->get();

        return response()->json($result, 200);
    }
}
