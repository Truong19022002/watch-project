<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function get(Request $request)
    {
        $result = DB::table('tchatlieu')->get();

        return response()->json($result, 200);
    }
}
