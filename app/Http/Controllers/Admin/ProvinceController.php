<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Province;
use Illuminate\Http\Request;


class ProvinceController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.provinces.index')->only('index');
        $this->middleware('can:admin.provinces.create')->only('create', 'store');
        $this->middleware('can:admin.provinces.edit')->only('edit', 'update');
        $this->middleware('can:admin.provinces.destroy')->only('destroy');
    }
    public function index(Request $request)
    {
        if ($request->has('department_id')) {
            $department_id = $request->input('department_id');
            session(['department_id' => $department_id]);
        } elseif (!session()->has('department_id')) {
            $firstDepartment = Department::first();
            if ($firstDepartment) {
                session(key: ['department_id' => $firstDepartment->id]);
            }
        }
        $department_id = session('department_id');
        $department = Department::find($department_id);
        $provinces = collect();
        if ($department) {
            $provinces = Province::where('department_id', $department->id)
                ->orderBy('name')
                ->get();
            return view('admin.provinces.index', compact('provinces', 'department'));
        } else {
            return redirect()->route('admin.departments.index', compact('department'))->with('info', 'No files found!.');
        }




    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $department_id = $request->input('department_id');
        $department = Department::find($department_id);
        if (!$department) {
            return redirect()->route('admin.provinces.index')->with('alert', 'Invalid department selected.');
        }
        $province = new Province(
            [
                'code' => '',
                'name' => '',
                'department_id' => $department_id,
            ]
        );

        return view('admin.provinces.create', compact('province'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Seleccionamos el departamento
        $department_id = $request->input('department_id');

        $department = Department::find($department_id);

        $code = $request->input('code');
        $lastProvince = Province::where('department_id', $department_id)
            ->orderBy('code', 'desc')
            ->first();

        if ($code == null || $code == '') {
            $code = $lastProvince ? str_pad((int) $lastProvince->code + 1, 4, '0', STR_PAD_LEFT) : $department_id . '01';
        } elseif (strlen($code) == 1) {
            $code = $department->code . '0' . $code;
        } elseif (strlen($code) == 2) {
            $code = $department->code . $code;
        } elseif ($code && substr($code, 0, 2) != $department->code) {
            return redirect()->back()->withErrors(['code' => 'The code does not correspond to the department.'])->withInput();
        } else {
            $code = $department->code . substr($code, -2);
        }
        $request->merge(['code' => $code]);

        $data = $request->validate(rules: [
            'code' => 'nullable|string|max:4|unique:provinces,code',
            'name' => 'required|string|max:100|unique:provinces,name',
            'department_id' => 'required|exists:departments,id'
        ]);
        Province::create($data);

        return redirect()->route('admin.provinces.index', 'department')->with('alert', [
                    'icon' => 'success',
                    'title' => 'Success!',
                    'text' => 'The province has been successfully created!.',
                    'timer' => 3000,
                ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        $departments = Department::all();
        $selectedDepartment = Department::find($province->department_id);
        return view('admin.provinces.edit', compact('departments', 'selectedDepartment', 'province'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Province $province)
    {
        
        $department = Department::find($province->department_id);
        $request->validate([
            'code' => 'required|string|max:4|unique:provinces,code,' . $province->id,
            'name' => 'required|string|max:255|unique:provinces,name,' . $province->id,
            'department_id' => 'required|exists:departments,id',
        ]);

        $province->update($request->only('code', 'name', 'department_id'));

        return redirect()->route('admin.provinces.index', ['department_id' => $department->id])->with('alert', [
                    'icon' => 'success',
                    'title' => 'Success!',
                    'text' => 'the province has been successfully updated!.',
                    'timer' => 3000,
                ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {

        $province->delete();
        $department = Department::find(session('department_id'));
        
        return redirect()->route('admin.provinces.index', compact('department'))->with('alert', [
                    'icon' => 'success',
                    'title' => 'Success!',
                    'text' => 'the province has been successfully eliminated!.',
                    'timer' => 3000,
                ]);
    }

}
