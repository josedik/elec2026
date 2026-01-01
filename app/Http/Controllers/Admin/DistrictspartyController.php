<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Party;
use Illuminate\Http\Request;

use function Psy\sh;

class DistrictspartyController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.districtsparty.index')->only('index','show');
//        $this->middleware('can:admin.districtsparty.show')->only('show');
        $this->middleware('can:admin.districtsparty.edit')->only('edit', 'update');
        $this->middleware('can:admin.districtsparty.create')->only('create', 'store');
    }
    public function index()
    {
        $districts = District::orderBy('code')
            ->get();
        return view('admin.districtsparty.index', compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parties.*' => 'exists:parties,id',
        ]);
        $district = District::find($request->district_id);
        if (!$district) {
            return redirect()->route('admin.districtsparty.index')->with('alert', [
                'icon' => 'error',
                'title' => 'Error!',
                'text' => 'The district not found!',
            ]);
        }
        $prefijo = substr($district->code, 0, 4);
        $end = substr($district->code, -2);
        $parties = $request->parties;

        if ($end == '01') {
            $distritos = District::where('code', 'like', $prefijo . '%')->get();
           
            foreach ($distritos as $district) {
                $district->parties()->sync($parties);
            }
        } else {
            $district->parties()->sync($request->parties);
        }


        return redirect()->route('admin.districtsparty.index')->with('alert', [
                'icon' => 'success',
                'title' => 'Success!',
                'text' => 'The political parties have been correctly assigned!',
            ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Logic to assign parties to the district
        $district = District::find($id)->findOrFail($id);
        $parties = $district->parties;
        return view('admin.districtsparty.viewparties', compact('district', 'parties'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Logic to assign parties to the district
        $district = District::find($id)->findOrFail($id);
        $parties = Party::all();
        return view('admin.districtsparty.assignParties', compact('district', 'parties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'escanios' => 'required|integer|min:5|max:39',
        ]);
        $district = District::find($id)->findOrFail($id);
        $district->escanios = $request->escanios;
        
        $district->save();
        return redirect()->route('admin.districtsparty.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'The number of regidores has been updated correctly!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
