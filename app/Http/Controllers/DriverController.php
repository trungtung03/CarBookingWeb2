<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'message' => 'Welcome to the Driver Dashboard',
            'user' => auth()->user()
        ]);
    }
}
