<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CCHDController extends Controller
{
    public function get(Request $request)
    {
        $result = DB::table('tcchd')->get();

        return response()->json($result, 200);
    }
}
