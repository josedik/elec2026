<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use Illuminate\Http\Request;

class DataTableController extends Controller
{
    public function mesas(){
//Seleccionar id, code, name of district and electors from mesas y unir con district para mostrar el nombre del distrito en lugar del id, ademas agregar una columna de accciones para editar y eliminar cada mesa
        $mesas = Mesa::select('id', 'code', 'district_id', 'electors')
        ->with('district:id,name')
        ->get();
        

        return datatables()->of($mesas)->toJson();
        
    }
}
