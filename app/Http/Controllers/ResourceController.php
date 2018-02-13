<?php


namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;
use App\Resource as MyResource;
use Illuminate\Http\Resources\Json\Resource;

class ResourceController extends Controller
{


    /**
     * Contains the list of fillable elements ( from the database )
     * @var array
     */
    protected $fillable = ['title', 'description', 'google_drive', 'publish_year', 'module_id'];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $resources = MyResource::latest()->paginate(5);

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
        $modules = Module::get(['name', 'degree', 'semester', 'code'])->sortBy('semester')->groupBy('degree')->toArray();

        $m_form = array();
        foreach ($modules as $degree_key => $degree) {

            foreach ($degree as $k_module => $module) {
                $m_form[$degree_key][$k_module] = 'S' . $module['semester'] . ' > ' . $module['name'];
            }
        }

        array_key_exists('L', $m_form) ? ($m_form['Licence'] = $m_form['L'] AND $m_form = array_diff_key($m_form, array_flip((array)['L']))) : NULL;
        array_key_exists('M', $m_form) ? ($m_form['Master'] = $m_form['M'] AND $m_form = array_diff_key($m_form, array_flip((array)['M']))) : NULL;
        array_key_exists('D', $m_form) ? ($m_form['Doctorat'] = $m_form['D'] AND $m_form = array_diff_key($m_form, array_flip((array)['D']))) : NULL;

        $modules = $m_form;


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
        //
        /*exit(var_dump($request->all()));*/
//        exit(var_dump($request->get('publish_year')));
        $request->validate(['title' => 'required|string', 'description' => 'nullable|string', 'google_drive' => 'nullable|url|max:255', 'publish_year' => 'required|numeric|digits:4|between:2008,' . date('Y')]);
        MyResource::create($request->all());
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
        return view('resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $resource = MyResource::find($id);
        return view('resources.edit', compact('resource'));
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
        $request->validate(['title' => 'required|string', 'description' => 'nullable|string', 'google_drive' => 'nullable|url|max:255', 'publish_year' => 'required|numeric|digits:4|between:2008,' . date('Y')]);
        MyResource::find($id)->update($request->all());

        return redirect()->route('resources.index')
            ->with('success', 'Resource updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MyResource::find($id)->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Resource deleted successfully');
    }
}
