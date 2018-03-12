<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Module;
use App\Resource;
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
            $params_data = ['degree' => $request->route('degree'), 'semester' => $request->route('semester'), 'module' => $request->route('module')];
            $latest_announcement = Announcement::all()->where('planned_time', '<=', Carbon::now()->toDateTimeString())->last();
            if ($params_data['degree'] !== null && $params_data['semester'] === null) {
                $params_data['semesters'] = Module::where(['degree' => $params_data['degree']])->distinct()->pluck('semester')->toArray();
                if ($params_data['semesters'] == null) {
                    abort('404', 'No Resources for this Semester');
                }
            } else {
                if ($params_data['semester'] !== null && $params_data['module'] === null) {
                    $params_data['modules'] = Module::where(['degree' => $params_data['degree'], 'semester' => $params_data['semester']])->has('resources')->distinct()->get();
                    if ($params_data['modules'] == null) {
                        abort('404', 'No Resources for this Semester');
                    }
                } elseif ($params_data['semester'] !== null && $params_data['module'] !== null) {

                    $params_data['resources'] = Resource::whereHas('module', function ($q) use ($params_data) {
                        $q->where('code', $params_data['module']);
                    })->latest()->get();
                    if ($params_data['resources'] == null) {
                        abort('404', 'No Resources for this Semester');
                    }

                }
            }
            return view('home', compact('params_data', 'latest_announcement'));
        }
    }
}
