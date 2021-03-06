<?php


namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;
use App\Resource as MyResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware;

class ResourceController extends Controller
{


    /**
     * Contains the list of fillable elements ( from the database )
     * @var array
     */
    protected $fillable = ['title', 'description', 'google_drive', 'publish_year', 'module_id'];


    protected $casts = ['module_id' => 'int'];

    /**
     * ResourceController constructor.
     * @param array $fillable
     */
    public function __construct()
    {
        $this->middleware(['auth', 'isTeacherORisAdmin'], ['except' => ['show']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        if (Auth::user()->hasRole('Admin')) {
            $resources = MyResource::latest()->paginate(5);
        } else {
            $resources = MyResource::where('user_id', Auth::user()->id)->latest()->paginate(5);
        }

        return view('resources.index', compact('resources'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $modules = Module::getFormModulesArray();

        return view('resources.create', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string', 'description' => 'nullable|string', 'google_drive' => 'nullable|url|max:255', 'publish_year' => 'required|numeric|digits:4|between:2008,' . date('Y'), 'module_id' => 'required|integer|exists:modules,id']);
        MyResource::create($request->all())->user()->associate($request->user())->save();
        return redirect()->route('resources.index')->with('success', 'Resource Added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $resource = MyResource::find($id);
        if ($resource != null) {
            return view('resources.show', compact('resource'));
        }
        return abort(404, 'Resource Not Found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $resource = MyResource::find($id);
        if ($resource != null) {
            if ($request->user()->id == $resource->user->id || $request->user()->hasRole('Admin')) {
                $modules = Module::getFormModulesArray();
                return view('resources.edit', compact('resource', 'modules'));
            } else {
                return abort(401, 'You\'re not allowed to edit this resource!');
            }
        }
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
        $resource = MyResource::find($id);
        if ($resource != null) {
            if ($resource->user->id == $request->user()->id || $request->user()->hasRole('Admin')) {
                $request->merge(['module_id' => $request->get('module_id')]);
                $request->validate(['title' => 'required|string', 'description' => 'nullable|string', 'google_drive' => 'nullable|url|max:255', 'publish_year' => 'required|numeric|digits:4|between:2008,' . date('Y'), 'module_id' => 'required|integer|exists:modules,id']);
                $resource->update($request->all());

                return redirect()->route('resources.index')
                    ->with('success', 'Resource updated successfully');
            }
            return abort(401, 'You\'re not allowed to edit this resource!');
        }
        return abort('404', 'User not found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $resource = MyResource::find($id);
        if ($resource != null) {
            if ($resource->user->id == $request->user()->id || $request->user()->hasRole('Admin')) {
                $resource->delete();

                return redirect()->route('resources.index')
                    ->with('success', 'Resource deleted successfully!');
            }
            return abort('401', 'You\'re not allowed to delete this resource!');
        }
    }
}
