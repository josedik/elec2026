<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\District;
use App\Models\Party;
use App\Models\Province;
use Generator;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
   public function __construct()
    {
        $this->middleware('can:admin.districts.index')->only('index');
        $this->middleware('can:admin.districts.create')->only('create', 'store');
        $this->middleware('can:admin.districts.edit')->only('edit', 'update');
        $this->middleware('can:admin.districts.destroy')->only('destroy');
    }
    public function index(Request $request)
    {
        $province = Province::find($request->input('province_id'));
        if (!$province) {
            return redirect()->route('admin.provinces.index');
        }
        $districts = District::where('province_id', $province->id)
            ->orderBy('code')
            ->get();

        return view('admin.districts.index', compact('districts', 'province'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $province = Province::find($request->input('province_id'));
        if (session('department_id') != $province->department_id) {
            return redirect()->route('admin.provinces.index');
        }
        $district = new District();
        $district->province_id = $province->id;

        return view('admin.districts.create', compact('district'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $code = $request->input('code');
        switch (true) {
            case strlen($code) == 6:
                break;
            case strlen($code) == 0:
                $code = $this->generateDistrictCode($request->input('province_id'));
                $request->merge(['code' => $code]);
                break;
            case strlen($code) == 1:
                $province = Province::find($request->input('province_id'));
                $codProv = substr($province->code, 0, 4);
                $code = $codProv . '0' . $code;
                $request->merge(['code' => $code]);
                break;
            case strlen($code) == 2:
                $province = Province::find($request->input('province_id'));
                $codProv = substr($province->code, 0, 4);
                $code = $codProv . $code;
                $request->merge(['code' => $code]);
                break;
            default:
                if (strlen($code) > 2) {
                    $code = $this->generateDistrictCode($request->input('province_id'));
                    $request->merge(['code' => $code]);
                }
        }
        $request->validate([
            'name' => 'required|unique:districts,name',
            'code' => 'required|unique:districts,code'
        ]);
        $district = new District($request->all());
        $province = Province::find($request->input('province_id'));

        $district->save();

        return redirect()->route('admin.districts.index', ['province_id' => $province->id])->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'the district has been successfully created!.',
            'timer' => 3000,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
     $districts = District::orderBy('name')->get();
     return view('admin.districts.show', compact('districts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(District $district)
    {
        $province = Province::find($district->province_id);
        return view('admin.districts.edit', compact('district', 'province'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, District $district)
    {
        $province = Province::find($request->input('province_id'));
        $codProv = $province->code;
        
        if ($codProv != substr($request->input('code'), 0, 4)) {
            return redirect()->route('admin.districts.index', ['province_id' => $province->id])->with('alert', [
                'icon' => 'error',
                'title' => 'Error!',
                'text' => 'Different province, please verify province code.!',
                'timer' => 3000,
            ]);
        }
        
        $request->validate([
            'code' => 'required|unique:districts,code,' . $district->id,
            'name' => 'required|unique:districts,name, ' . $district->id,
        ]);
        $district->update($request->all());


        return redirect()->route('admin.districts.index', ['province_id' => $province->id])->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'the district has been successfully updated!.',
            'timer' => 3000,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district)
    {
        $province = Province::find($district->province_id);
        $district->delete();

        return redirect()->route('admin.districts.index', ['province_id' => $province->id])->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'the district has been successfully deleted!.',
            'timer' => 3000,
        ]);
    }

    public function generateDistrictCode($provinceId)
    {
        $province = Province::find($provinceId);
        $codProv = substr($province->code, 0, 4);

        $lastDistrict = District::where('province_id', $provinceId)
            ->orderBy('code', 'desc')
            ->first();

        if ($lastDistrict) {
            $lastCode = (int) substr($lastDistrict->code, 4, 2);
            $newCode = str_pad($lastCode + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $newCode = '01';
        }

        return $codProv . $newCode;
    }

    public function assignParties(District $district)
    {
        // Logic to assign parties to the district
        $parties = Party::all();
        return view('admin.districts.assignParties', compact('district','parties'));
    }
}

