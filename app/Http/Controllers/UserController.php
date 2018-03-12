<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $fillable = ['name',];

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //


        if ($request->route('grade') !== null) {
            $grades = ['students' => 'S', 'teachers' => 'T', 'admins' => 'A'];
            $grade = $grades[$request->route('grade')];
            $users = User::where('role', $grade)->latest()->paginate(5);
        } else {
            $users = User::latest()->paginate(5);
        }


        return view('users.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate(['name' => 'required|string|max:255', 'email' => 'required|string|email|max:255|unique:users', 'password' => 'required|string|min:6']);
        $request->merge(['password' => bcrypt($request->get('password'))]);
        User::create($request->all());
        return redirect()->route('users.index')->with('success', 'User Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        if ($user != null) {
            return view('users.edit', compact('user'));
        }
        return abort('404', 'User Not found!');

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
        //
        $user = User::find($id);

        if ($user != null) {
            $request->validate(['name' => 'required|string|max:255', 'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, 'password' => 'required|string|min:6', 'role' => 'required|in:S,T,A']);
            if ($request->get('password') != $user->password) {
                $request->merge(['password' => bcrypt($request->get('password'))]);
            }
//            exit($request->get('role'));
            $user->update($request->all());
            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');

        }
        abort('404', 'User not found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::find($id);
        if ($user != null) {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully!');

        }
        return abort('404', 'User not found!');
    }
}
