<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['Admin', 'Teacher']);
        if ($request->user()->hasAnyRole(['Admin'])) {
            return redirect()->route('announcements.index')->with('success', 'Welcome Back, Mr. ' . $request->user()->name);
        } elseif ($request->user()->hasAnyRole(['Teacher'])) {
            return redirect()->route('resources.index')->with('success', 'Welcome Back, Mr. ' . $request->user()->name);
        }
    }
}
