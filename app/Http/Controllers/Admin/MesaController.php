<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Mesa;
use App\Models\Party;
use App\Models\School;

use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.mesas.index')->only('index', 'storeVotes');
        $this->middleware('can:admin.mesas.create')->only('create', 'store');
        $this->middleware('can:admin.mesas.edit')->only('edit', 'update');
        $this->middleware('can:admin.mesas.destroy')->only('destroy');
        $this->middleware('can:admin.mesas.show')->only('show');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $search .= "";
        $mesas = Mesa::where('code', 'LIKE', "%{$search}%")
            ->orWhere('dnii', 'LIKE', "%{$search}%")
            ->orWhere('dnif', 'LIKE', "%{$search}%")
            ->orWhereHas('district', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->orderBy('code', 'asc')
            ->paginate()
            ->appends(['search' => $search]);
        return view('admin.mesas.index', compact('mesas')); 
        
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $schools = School::orderBy('district_id')
            ->get();
        $mesa = new Mesa();
        return view('admin.mesas.create', compact('mesa', 'schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([

            'code' => 'required|string|unique:mesas',
            'electors' => 'required',
            'school_id' => 'required',
        ]);

        Mesa::create($data);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Well done!',
            'text' => 'Successfully created table'
        ]);

        return redirect()->route('admin.mesas.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'The polling station was successfully created!.',
        ]);
        ;
    }

    /**
     * Display the specified resource.
     */
    public function show(Mesa $mesa)
    {
        $parties = Party::whereHas('districts', function ($query) use ($mesa) {
            $query->where('district_id', $mesa->district_id);
        })->get();


        if ($mesa->district->capital == 'yes') {
            $isCapital = true;
        } else {
            $isCapital = false;
        }
        return view('admin.mesas.show', compact('mesa', 'parties', 'isCapital'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mesa $mesa)
    {
        $schools = School::orderBy('name')
            ->get();
        return view('admin.mesas.edit', data: compact('mesa', 'schools'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mesa $mesa)
    {
        $request->validate([

            'code' => 'required|unique:mesas,id,' . $mesa->id,
            'electors' => 'required',
            'school_id' => 'required|exists:schools,id',

        ]);

        $mesa->update($request->all());

        return redirect()->route('admin.mesas.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'The polling station was successfully updated!.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mesa $mesa)
    {
        $mesa->delete();

        return redirect()->route('admin.mesas.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'The polling station was successfully deleted!.',
        ]);
    }

    public function storeVotes(Request $request, Mesa $mesa)
    {
        $votesTotal = $request->input('votes_province.' . 'total', 0);
        $isCapital = $request->input('isCapital');

        if ($votesTotal > $mesa->electors) {
            return redirect()->back()->with('alert', [
                'icon' => 'error',
                'title' => 'Error!',
                'text' => 'The total number of votes cannot exceed the number of registered electors (' . $mesa->electors . ').',
                'timer' => 5000,
            ])->withInput();
        }
        if ($votesTotal <= 0) {
            return redirect()->route('admin.mesas.index')->with('alert', [
                'icon' => 'error',
                'title' => 'Error!',
                'text' => 'The total number of province votes must be greater than zero.',
                'timer' => 3000,
            ])->withInput();
        }
        $votesTotal = $request->input('votes_district.' . 'total', 0);
        if ($votesTotal > $mesa->electors) {
            return redirect()->back()->with('alert', [
                'icon' => 'error',
                'title' => 'Error!',
                'text' => 'The total number of votes cannot exceed the number of registered electors (' . $mesa->electors . ').',
                'timer' => 5000,
            ])->withInput();
        }
        if ($votesTotal <= 0 && $isCapital == 0) {
            return redirect()->route('admin.mesas.index')->with('alert', [
                'icon' => 'error',
                'title' => 'Error!',
                'text' => 'The total number of district votes must be greater than zero.',
                'timer' => 3000,
            ])->withInput();
        }


        $parties = Party::whereHas('districts', function ($query) use ($mesa) {
            $query->where('district_id', $mesa->district_id);
        })->get();
        $request->validate([
            'votes_province.*' => 'integer|min:0',
            'votes_district.*' => 'integer|min:0',
        ]);
        foreach ($parties as $party) {
            $votesProvince = $request->input('votes_province.' . $party->id, 0);
            $votesDistrict = $request->input('votes_district.' . $party->id, 0);

            /* $mesa->parties()->attach([
                'votes_province' => $votesProvince,
                'votes_district' => $votesDistrict
            ]); */
            $mesa->parties()->syncWithoutDetaching([
                $party->id => [
                    'votes_province' => $votesProvince,
                    'votes_district' => $votesDistrict,
                    //'votes' => $request->input('votes.' . $party->id, 0)
                ]
            ]);
        }
        return redirect()->route('admin.mesas.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'Votes have been successfully recorded!.',
        ]);

    }
}
