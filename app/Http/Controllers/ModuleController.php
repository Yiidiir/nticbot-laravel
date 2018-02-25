<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\Mod;

class ModuleController extends Controller
{
    protected $fillable = ['name', 'code', 'description', 'degree', 'semester'];


    protected $casts = ['semester' => 'int'];

    /**
     * ModuleController constructor.
     *
     * @param $fillable array
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
    public function index()
    {
        $modules = Module::latest()->paginate(5);
        return view('modules.index', compact('modules'))
            ->with('i', (request()->input('page', 1) - 1) * 5);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('modules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string', 'code' => 'required|string|alpha_num', 'description' => 'required|string|max:255', 'degree' => 'required|in:L,M,D', 'semester' => 'required|integer|between:1,6']);
        Module::create($request->all());
        return redirect()->route('modules.index')->with('success', 'Module Added!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('modules.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module = Module::find($id);
        return view('modules.edit', compact('module'));
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
        $request->validate(['name' => 'required|string', 'code' => 'required|string|alpha_num', 'description' => 'required|string|max:255', 'degree' => 'required|in:L,M,D', 'semester' => 'required|integer|between:1,6']);
        Module::find($id)->update($request->all());

        return redirect()->route('modules.index')
            ->with('success', 'Module updated successfully');
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
        Module::find($id)->delete();

        return redirect()->route('modules.index')
            ->with('success', 'Module deleted successfully');
    }
}
