<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.departments.index')->only('index');
        $this->middleware('can:admin.departments.create')->only('create', 'store');
        $this->middleware('can:admin.departments.edit')->only('edit', 'update');
        $this->middleware('can:admin.departments.destroy')->only('destroy');
        $this->middleware('can:admin.departments.show')->only('show');
    }
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $department = new Department();
        return view('admin.departments.create', compact('department'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'code' => 'nullable|digits:2|unique:departments,code',
        ]);
        if (strlen($request->code) == 0) {
            $maxCode = Department::max('code');
            $request->merge(['code' => str_pad($maxCode + 1, 2, '0', STR_PAD_LEFT)]);
        }
        if (strlen($request->code) == 1) {
            $request->merge(['code' => str_pad($request->code, 2, '0', STR_PAD_LEFT)]);
        }


        $department = Department::create($request->only('name', 'code'));

        return redirect()->route('admin.departments.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'The department was successfully created!.',
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit', data: compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'code' => 'nullable|digits:2|unique:departments,code,' . $department->id,
        ]);
        if (strlen($request->code) == 0) {
            $maxCode = Department::max('code');
            $request->merge(['code' => str_pad($maxCode + 1, 2, '0', STR_PAD_LEFT)]);
        }
        if (strlen($request->code) == 1) {
            $request->merge(['code' => str_pad($request->code, 2, '0', STR_PAD_LEFT)]);
        }
        $department->update($request->only('name', 'code'));

        return redirect()->route('admin.departments.index')->with('alert', [
                    'icon' => 'success',
                    'title' => 'Success!',
                    'text' => 'The department has been successfully updated!.',
                ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {

        try {
            $department->delete();
            session()->flash('alert', 'Eliminado con éxito!');
            return redirect()->route('admin.departments.index')
                ->with('alert', [
                    'icon' => 'success',
                    'title' => 'Success!',
                    'text' => 'Department was deleted successfully.',
                ]);
        } catch (\Exception $e) {
            session()->flash('alert', '¡Operación realizada con éxito!');
            return redirect()->route('admin.departments.index')
                ->with('alert', [
                    'icon' => 'success',
                    'title' => 'Error!',
                    'text' => 'The record could not be deleted!.',
                ]);
        }
    }
}
