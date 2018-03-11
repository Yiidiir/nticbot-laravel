<?php

namespace App\Http\Controllers;

use App\Announcement;
use Carbon\Carbon;
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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->user() !== null) {
            if ($request->user()->hasAnyRole(['Admin'])) {
                return redirect()->route('announcements.index')->with('success', 'Welcome Back, Mr. ' . $request->user()->name);
            } elseif ($request->user()->hasAnyRole(['Teacher'])) {
                return redirect()->route('resources.index')->with('success', 'Welcome Back, Mr. ' . $request->user()->name);
            }
        } else {
            $params = ['degree' => $request->route('degree'), 'semester' => $request->route('semester'), 'module'=>$request->route('module')];
            $latest_announcement = Announcement::all()->where('planned_time', '<=', Carbon::now()->toDateTimeString())->last();
            return view('home', compact('params','latest_announcement'));
        }
    }
}
