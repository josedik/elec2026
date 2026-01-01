<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mesa;
use Illuminate\Routing\Controller;
use App\Models\Voter;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function __construct(){
        $this->middleware('can:admin.voters.index')->only('index','show');
        $this->middleware('can:admin.voters.create')->only('create','store');
        $this->middleware('can:admin.voters.edit')->only('edit','update');
        $this->middleware('can:admin.voters.destroy')->only('destroy');

    }
    public function index()
    {
        $voters = Voter::orderBy('surname')->get();
        return view('admin.voters.index',compact('voters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mesas = Mesa::orderBy('code')->get();
        $voter = new Voter();
        return view('admin.voters.create',compact('voter','mesas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'dni' => 'required|string|max:50|unique:voters,dni',
            'date_of_birth' => 'required|date',
            'mesa_id' => 'required|exists:mesas,id',
            'active' => 'sometimes|boolean',
        ]);

        $validated['active'] = $request->has('active') ? 1 : 0;

        Voter::create($validated);

        return redirect()->route('admin.voters.index')->with('success', 'Voter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Voter $voter)
    {
        //
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
            'dni' => 'required|string|max:50|unique:voters,dni,'.$voter->id,
            'date_of_birth' => 'required|date',
            'mesa_id' => 'required|exists:mesas,id',
            'active' => 'sometimes|boolean',
        ]);

        $validated['active'] = $request->has('active') ? 1 : 0;

        $voter->update($validated);

        return redirect()->route('admin.voters.index')->with('success', 'Voter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voter $voter)
    {
       //eliminar registro
    $voter->delete();

    return redirect()->route('admin.voters.index')->with('success', 'Voter deleted successfully.');
    }
}
