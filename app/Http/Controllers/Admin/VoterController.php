<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mesa;
use Illuminate\Routing\Controller;
use App\Models\Voter;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class VoterController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.voters.index')->only('index', 'show');
        $this->middleware('can:admin.voters.create')->only('create', 'store');
        $this->middleware('can:admin.voters.edit')->only('edit', 'update');
        $this->middleware('can:admin.voters.destroy')->only('destroy');

    }
    public function index()
    {
        $voters = Voter::orderBy('surname')->get();

        return view('admin.voters.index', compact('voters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mesas = Mesa::orderBy('code')->get();
        $voter = new Voter();
        return view('admin.voters.create', compact('voter', 'mesas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|string|max:50|unique:voters,dni',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'mesa_id' => 'required|exists:mesas,id',
            'active' => 'sometimes|boolean',
        ]);

        //$validated['active'] = $request->has('active') ? 1 : 0;

        Voter::create($validated);

        return redirect()->route('admin.voters.index')->with('success', 'Voter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Voter $voter)
    {
        //mostrar datos de voter que coincida con el dni. 
        //Utilizar scopeSearchDni metodo en el modelo Voter
        $voter = Voter::searchDni($request->dni)->firstOrFail();

        return $voter;

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voter $voter)
    {
        $mesas = Mesa::orderBy('code')->get();
        return view('admin.voters.edit', compact('voter', 'mesas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voter $voter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'dni' => 'required|string|max:50|unique:voters,dni,' . $voter->id,
            'date_of_birth' => 'required|date',
            'mesa_id' => 'required|exists:mesas,id',
            'active' => 'sometimes|boolean',
        ]);

        //$validated['active'] = $request->has('active') ? 1 : 0;


        $voter->update($validated);

        return redirect()->route('admin.voters.index')->with('success', 'Voter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        //Se elimina a solicitud ajax desde la vista
        
        if ($request->ajax()) {
            try {
                $voter = Voter::find($id);
                $voter->delete();
                return response()->json(['success' => true, 'message' => 'Voter deleted successfully.']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Unable to delete voter.'], 500);
            }
        }

        return redirect()->route('admin.voters.index');
    }

    public function getVoters(Request $request)
    {
        if ($request->ajax()) {
            $data = Voter::select('*');
            
            return datatables()->of(Voter::query())
                ->addIndexColumn()
                ->addColumn('action',function($row){
                    $btn = '<a href="/admin/voters/'.$row->id.'/edit" class="btn btn-primary btn-sm" title="Edit voter"><i class="fa fa-pen"></i></a>'.
                    '<button title="Delete voter with DNI '.$row->dni.'"  class="btn btn-danger btn-sm ml-1" id="btn-'.$row->id.'"><i class="fa fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();

        }
        abort(404, 'This page is currently invalid, please try again later.');

    }
// ...existing code...
}
