<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BrandController extends Controller
{
    public function get(Request $request)
    {
        $result = DB::table('tthuonghieu')->get();

        return response()->json($result, 200);
    }
}
