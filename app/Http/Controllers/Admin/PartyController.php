<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\Party;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PartyController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.parties.index')->only(['index']);
        $this->middleware('can:admin.parties.create')->only(['create', 'store']);
        $this->middleware('can:admin.parties.edit')->only(['edit', 'update']);
        $this->middleware('can:admin.parties.destroy')->only(['destroy']);
    }
    public function index()
    {
        $parties = Party::all();

        return view('admin.parties.index', compact('parties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $voters = Voter::orderBy('name')
            ->get();
        $logo = 'logo.png';
        //return parties;
        $party = new Party();
        return view('admin.parties.create', compact('party', 'voters', 'logo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (empty($request->input('code'))) {
            $maxCode = Party::max('code');
            $request->merge(['code' => str_pad($maxCode + 1, 4, '0', STR_PAD_LEFT)]);
        }
        if (empty($request->input('logo_path'))) {
            $request->merge(['logo_path' => 'images/logo.png']);
        }
        if($request->input('voters') === null){
            $request->merge(['voters' => 0]);
        }
        $request->validate([
            'code' => 'required|unique:parties,code',
            'name' => 'required',
            'acronym' => 'required',
            'voter_id' => 'required|exists:voters,id',
            'active' => 'required|boolean',
            'logo_path' => 'nullable',
            'order' => 'required',
        ]);


        $lo = $request->file('logo_path');
        $filename = '';
        if ($lo) {
            $filename = $request->input('code') . '.' . $lo->getClientOriginalExtension();
            $path = $lo->storeAs('images', $filename); // Guarda la imagen en storage/app/public/images
        } else {
            $path = 'images/logo.png';
        }
        $url = Storage::url($path);


        $data = $request->only(['code', 'name', 'acronym', 'voter_id', 'voters', 'active','order']);
        //$data['logo_path'] = $url;

        $party = Party::create($data);
        $party->logo_path = $filename;
        $party->save();

        return redirect()->route('admin.parties.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'The Political party has been successfully created!.',
            'timer' => 3000
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Party $party)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Party $party)
    {
        $voters = Voter::orderBy('name')
            ->get();
        $logo = $party->logo_path;
        return view('admin.parties.edit', data: compact('party', 'voters', 'logo'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Party $party)
    {

        $lo = $request->file('logo_path');
        
        if ($lo) {
            $filename = $request->input('code') . '.' . $lo->getClientOriginalExtension();
            $path = $lo->storeAs('images', $filename); // Guarda la imagen en storage/app/public/images
        } else {
            if ($party->logo_path) {
                $path = $party->logo_path;
            } else {
                $path = 'logo.png';
            }

        }
        $url = Storage::url($path);

        $request->validate([
            'code' => 'required|unique:parties,code,' . $party->id,
            'name' => 'required',
            'acronym' => 'required',
            'voter_id' => 'required|exists:voters,id',
            'active' => 'required|boolean',
            'order' => 'required',

        ]);
        if ($lo) {
            // Delete old logo if exists and is not the default logo
            if ($party->logo_path && $party->logo_path !== 'logo.png') {
                if (Storage::exists($party->logo_path)) {
                    Storage::delete($party->logo_path);
                }
            }
            $party->logo_path = $path;
        }
        $file = explode('/', $path);
        $file = end($file);
        $party->update($request->all());
        $party->logo_path = $file;
        $party->save();


        return redirect()->route('admin.parties.index', $party)->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'The Political party has been successfully updated!.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Party $party, Request $request)
    {
        if ($party->logo_path && $party->logo_path !== 'images/logo.png') {
            if (Storage::exists($party->logo_path)) {
                Storage::delete($party->logo_path);
            }
        }
        $party->delete();
        return redirect()->route('admin.parties.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'The Political party has been successfully deleted!.',
        ]);
    }

}
