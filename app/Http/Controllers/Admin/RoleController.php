<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
   public function __construct(){
        $this->middleware('can:admin')->only(['index', 'store','edit','update','destroy']);
        
        
    }
    public function index()
    {
        //
        $roles = Role::all();
        return view('admin.users.roles', compact('roles'));
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
        //
        $role = Role::create(['name' => $request->input('name')]);

        return back()->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //

        $permissions = Permission::all();
        return view('admin.users.rolePermissions', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
        //return $role;
        if($request->has('name')){
            $role->name = $request->input('name');
            $role->save();
            return back()->with('success', 'Role updated successfully.');
        }
        $permissions = $request->input('permissions', []);
        $permissions = array_map('intval', (array) $permissions);
        $permissions = array_values(array_filter($permissions));
        $permissions = Permission::whereIn('id', $permissions)->pluck('id')->toArray();
        $request->merge(['permissions' => $permissions]);
        $role->syncPermissions($request->input('permissions', []));
        return back()->with('success', 'Permissions updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findById($id);
        $role->delete();
        return back()->with('success', 'Role deleted successfully.');

    }
}
