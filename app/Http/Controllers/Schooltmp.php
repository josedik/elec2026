<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\District;
use App\Models\Mesa;
use App\Models\Party;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\DB;


class Schooltmp extends Controller
{
    public function obtenerIniciales(string $nombreCompleto)
    {
        // Convierte a mayúsculas para consistencia y divide por espacios
        $partes = explode(' ', strtoupper($nombreCompleto));
        $iniciales = '';

        // Itera sobre cada parte del nombre
        foreach ($partes as $parte) {
            // Añade la primera letra de cada parte si la parte no está vacía
            if (strlen($parte) > 0) {
                $iniciales .= substr($parte, 0, 1); // substr($parte, 0, 1) obtiene el primer carácter
            }
        }
        return $iniciales;
    }

    public function districts()
    {
        // function for import data of districts.
        $filename = public_path('storage/distritos.csv'); // Ruta al archivo CSV
        $tableName = 'districts'; // Nombre de la tabla donde se importarán los datos
        $delimiter = ';'; // Delimitador del archivo CSV
        $header = null; // Variable para almacenar los nombres de las columnas
        $data = []; // Array para almacenar los datos a insertar
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $code = str_pad($row[1], 6, '0', STR_PAD_LEFT);
                $codeDep = substr($code, 0, 2);
                $codePro = substr($code, 0, 4);
                $codeDis = $code;
                $nameDis = $row[2];
                $namePro = $row[3];
                $nameDep = $row[4];
                $popu = $row[5];
                $area = $row[6];
                $depa = Department::where('code', $codeDep)->first();
                $prov = Province::where('code', $codePro)->first();
                $dist = District::where('code', $codeDis)->first();
                if (!$dist) {
                    $nuevoDistrito = District::create([
                        'name' => $nameDis,
                        'code' => $codeDis,
                        'province_id' => $prov->id,
                        'population' => $popu,
                        'area' => $area,
                    ]);
                }

            }
            fclose($handle);
        }
        // Insertar los datos en la tabla

        //DB::table($tableName)->insert($data);
        return "Importación completada";
    }

    public function school()
    {
        $filename = public_path('storage/colegios.csv'); // Ruta al archivo CSV
        $tableName = 'schools'; // Nombre de la tabla donde se importarán los datos
        $delimiter = ';'; // Delimitador del archivo CSV
        $header = null; // Variable para almacenar los nombres de las columnas
        $data = []; // Array para almacenar los datos a insertar
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $code = str_pad($row[0], 5, '0', STR_PAD_LEFT);
                $name = $row[1];
                $dep = $row[2];
                $pro = $row[3];
                $dis = $row[4];
                $tables = $row[5];
                $voters = $row[6];
                $district = District::where('name', $dis)->first();
                $district_id = $district->id ?? 2;

                $schoolNew = School::create([
                    'code' => $code,
                    'name' => $name,
                    'tables' => $tables,
                    'district_id' => $district_id,
                    'voters' => $voters,
                ]);

            }
            fclose($handle);
        }
        // Insertar los datos en la tabla

        //DB::table($tableName)->insert($data);
        return "Importación completada";
    }

    public function mesas()
    {
        $filename = public_path('storage/mesas.csv'); // Ruta al archivo CSV
        $tableName = 'mesas'; // Nombre de la tabla donde se importarán los datos
        $delimiter = ';'; // Delimitador del archivo CSV
        $header = null; // Variable para almacenar los nombres de las columnas
        $data = []; // Array para almacenar los datos a insertar
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $code = str_pad(trim($row[1]), 6, '0', STR_PAD_LEFT);
                $electors = $row[2];
                $codeDis = str_pad(trim($row[3]), 6, '0', STR_PAD_LEFT);
                $district = District::where('code', '=', $codeDis)->first();
                if ($district !== null) {
                    $tableNew = Mesa::create([
                        'code' => $code,
                        'electors' => $electors,
                        'district_id' => $district->id,
                        'dnii' => '',
                        'dnif' => '',
                        'school_id' => 1,
                    ]);
                } else {
                    $tableNew = Mesa::create([
                        'code' => $code,
                        'electors' => $electors,
                        'district_id' => 1,
                        'dnii' => '',
                        'dnif' => '',
                        'school_id' => 1,
                    ]);
                }

            }
            fclose($handle);
        }

        // Insertar los datos en la tabla

        //DB::table($tableName)->insert($data);
        return "Importación completada";
    }

    public function codePad()
    {
        // Function to convert code to 6-character string, padding with leading zeros
        $mesas = Mesa::all();
        foreach ($mesas as $mesa) {
            $code = trim($mesa->code);
            $code = str_pad($code, 6, '0', STR_PAD_LEFT);
            $mesa->update([
                'code' => $code,
            ]);
        }
        return "Cambio completo";
    }

    public function departments()
    {
        // function for import data of districts.
        $filename = public_path('storage/distritos.csv'); // Ruta al archivo CSV
        $tableName = 'departments'; // Nombre de la tabla donde se importarán los datos
        $delimiter = ';'; // Delimitador del archivo CSV
        $header = null; // Variable para almacenar los nombres de las columnas
        $data = []; // Array para almacenar los datos a insertar
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $code = str_pad($row[1], 6, '0', STR_PAD_LEFT);
                $codeDep = substr($code, 0, 2);
                $nameDep = $row[4];
                $depa = Department::where('code', $codeDep)->first();
                if (!$depa) {
                    $nuevoDepa = Department::create([
                        'name' => $nameDep,
                        'code' => $codeDep,
                    ]);
                }

            }
            fclose($handle);
        }
        // Insertar los datos en la tabla
        //DB::table($tableName)->insert($data);
        return "Importación completada";
    }

    public function provinces()
    {
        // function for import data of districts.
        $filename = public_path('storage/distritos.csv'); // Ruta al archivo CSV
        $tableName = 'provinces'; // Nombre de la tabla donde se importarán los datos
        $delimiter = ';'; // Delimitador del archivo CSV
        $header = null; // Variable para almacenar los nombres de las columnas
        $data = []; // Array para almacenar los datos a insertar
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $code = str_pad($row[1], 6, '0', STR_PAD_LEFT);
                $codeDep = substr($code, 0, 2);
                $codePro = substr($code, 0, 4);
                $namePro = $row[3];
                $depa = Department::where('code', $codeDep)->first();
                $prov = Province::where('code', $codePro)->first();
                if (!$prov) {
                    $nuevaProv = Province::create([
                        'name' => $namePro,
                        'code' => $codePro,
                        'department_id' => $depa->id,
                    ]);
                }

            }
            fclose($handle);
            // Insertar los datos en la tabla

            //DB::table($tableName)->insert($data);
            return "Importación completada";
        }
    }

    public function parties()
    {
        // function for import data of districts.
        $filename = public_path('storage/partidos.csv'); // Ruta al archivo CSV
        $tableName = 'parties'; // Nombre de la tabla donde se importarán los datos
        $delimiter = ';'; // Delimitador del archivo CSV
        $header = null; // Variable para almacenar los nombres de las columnas
        $data = []; // Array para almacenar los datos a insertar
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $code = str_pad($row[0], 4, '0', STR_PAD_LEFT);
                $nameParty = $row[1];
                $voters = $row[2];
                $acronym = $this->obtenerIniciales($nameParty);
                $newParty = Party::create([
                    'name' => $nameParty,
                    'code' => $code,
                    'voters' => $voters,
                    'acronym' => $acronym,
                ]);
            }

        }
        fclose($handle);
        // Insertar los datos en la tabla

        //DB::table($tableName)->insert($data);
        return "Importación completada";
    }


}