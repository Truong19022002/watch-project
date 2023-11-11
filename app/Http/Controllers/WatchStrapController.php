<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class WatchStrapController extends Controller
{
    public function get(Request $request)
    {
        $result = DB::table('view_daydeo')->get();

        return response()->json($result, 200);
    }
}
