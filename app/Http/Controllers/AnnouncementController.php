<?php

namespace App\Http\Controllers;

use App\Announcement;
use Illuminate\Http\Request;
use DateTime;

class AnnouncementController extends Controller
{
    protected $fillable = ['body', 'planned_time'];

    /**
     * AnnouncementController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'isTeacherORisAdmin']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $announcements = Announcement::latest()->paginate(5);
        return view('announcements.index', compact('announcements'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('announcements.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        exit($request->get('planned_time_on'));
        if ($request->get('planned_time_on') == true) {
            $date = new DateTime($request->get('planned_time'));
            $hours = new DateTime($request->get('planned_time_time'));
            $planned_time = $date->format('Y-m-d') . ' ' . $hours->format('H:i:s');
            $request->merge(['planned_time' => $planned_time]);
        } else {
            $request->merge(['planned_time' => date_create('now')->format('Y-m-d H:i:s')]);
            $request->merge(['planned_time_on' => false]);
        }
        $request->merge(['user_id' => $request->user()->id]);
        $request->validate(['body' => 'required|string|max:65535', 'planned_time' => 'required_unless:planned_time_on,false|date_format:Y-m-d H:i:s|sometimes', 'planned_time_on' => 'boolean']);
        Announcement::create($request->all());
        return redirect()->route('announcements.index')->with('success', 'Announcement Add to Broadcast Queue!');


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $announcement = Announcement::find($id);
        return view('announcements.view', compact('announcement'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //
        $announcement = Announcement::find($id);
        if ($announcement != null) {

            if (date($announcement->planned_time) < now()) {
                return redirect()->route('announcements.index');
            } else {
                $announcement = Announcement::find($id);
                return view('announcements.edit', compact('announcement'));
            }
        }
        return abort('404', 'Announcement Not Found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $announcement = Announcement::find($id);
        if ($announcement != null) {
            if (date($announcement->planned_time) < now()) {
                return redirect()->route('announcements.index');
            } else {
                if ($request->get('planned_time_on') == true) {
                    $date = new DateTime($request->get('planned_time'));
                    $hours = new DateTime($request->get('planned_time_time'));
                    $planned_time = $date->format('Y-m-d') . ' ' . $hours->format('H:i:s');
                    $request->merge(['planned_time' => $planned_time]);
                } else {
                    $request->merge(['planned_time' => date_create('now')->format('Y-m-d H:i:s')]);
                    $request->merge(['planned_time_on' => false]);
                }
                $request->merge(['user_id' => $request->user()->id]);
                $request->validate(['body' => 'required|string|max:65535', 'planned_time' => 'required_unless:planned_time_on,false|date_format:Y-m-d H:i:s|sometimes', 'planned_time_on' => 'boolean']);
                $announcement->update($request->all());
                return redirect()->route('announcements.index')->with('success', 'Announcement Edited successfully!');

            }
        }
        return abort('404', 'Announcement Not Found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        $announcement = Announcement::find($id);
        if ($announcement != null) {
            if (date($announcement->planned_time) < now()) {
                return redirect()->route('announcements.index');
            } else {
                $announcement->delete();
                return redirect()->route('announcements.index')->with('success', 'Announcement Deleted successfully, No Broadcast done!');
            }
        }
        return abort('404', 'Announcement Not Found!');
    }
}
