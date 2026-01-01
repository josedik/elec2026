<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.schools.index')->only('index');
        $this->middleware('can:admin.schools.create')->only('create', 'store');
        $this->middleware('can:admin.schools.edit')->only('edit', 'update');
        $this->middleware('can:admin.schools.destroy')->only('destroy');
    }
    public function index()
    {
        $schools = School::all();
        return view('admin.schools.index',compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = District::orderBy('name')->get();
        $school = new School();
        return view('admin.schools.create',compact('districts','school'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cod = $request->input('code');
        if($cod){
            $cod = str_pad($cod, 5, '0', STR_PAD_LEFT);
        } else {
            $lastSchool = School::orderBy('code', 'desc')->first();
            if ($lastSchool) {
                $lastCode = (int)$lastSchool->code;
                $cod = str_pad($lastCode + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $cod = '00001';
            }
        }
        $cod = str_pad($cod, 5, '0', STR_PAD_LEFT);
        $request->merge(['code' => $cod]);
        $validated = $request->validate([
            'code' => 'required|string|max:5|unique:schools,code',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
        ]);

        // si existe nombre duplicado en el mismo distrito, error
        $existingSchool = School::where('name', $validated['name'])
                                ->where('district_id', $validated['district_id'])
                                ->first();
        if ($existingSchool) {
            return redirect()->back()->withInput()->withErrors(['name' => 'A school with this name already exists in the selected district.']);
        }
        $school = School::create($validated);

        return redirect()->route('admin.schools.index')->with('alert', [
                    'icon' => 'success',
                    'title' => 'Success!',
                    'text' => 'The school has been successfully created!.',
                ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
    /* with('alert', [
                    'icon' => 'success',
                    'title' => 'Success!',
                    'text' => 'The department has been successfully updated!.',
                ]); */
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        $districts = District::orderBy('name')->get();
        return view('admin.schools.edit',compact('school','districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $cod = $request->input('code');
        $cod = str_pad($cod, 5, '0', STR_PAD_LEFT);
        $request->merge(['code' => $cod]);
        $validated = $request->validate([
            'code' => 'required|string|max:6|unique:schools,code,'.$school->id,
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
        ]);
        // si existe nombre duplicado en el mismo distrito, error
        $existingSchool = School::where('name', $validated['name'])
                                ->where('district_id', $validated['district_id'])
                                ->where('id', '!=', $school->id)
                                ->first();
        if ($existingSchool) {
            return redirect()->back()->withInput()->withErrors(['name' => 'A school with this name already exists in the selected district.']);
        }

        $school->update($validated);

        return redirect()->route('admin.schools.index')->with('alert', [
                    'icon' => 'success',
                    'title' => 'Success!',
                    'text' => 'The school has been successfully updated!.',
                    'timer' => 3000,
                ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('admin.schools.index')->with('alert', [
                    'icon' => 'success',
                    'title' => 'Deleted!',
                    'text' => 'The school has been successfully deleted!.',
                ]);
    }

}
