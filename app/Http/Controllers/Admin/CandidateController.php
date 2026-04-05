<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\District;
use App\Models\Mesa;
use App\Models\Party;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Array_;
use Codedge\Fpdf\Fpdf\Fpdf;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $districts = District::orderBy('code')
            ->get();
        return view('admin.candidates.index', compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $district = District::findOrFail($request->input('district_id'));
        $party = Party::findOrFail($request->input('party_id'));

        $numeroFilas = $district->escanios; // Puedes hacerlo variable, por ejemplo, desde una petición o configuración

        $filasEnBlanco = [];
        for ($i = 0; $i < $numeroFilas + 1; $i++) {
            $filasEnBlanco[] = [
                'dni' => '',
                'name' => '',
                'surname' => '',
                'order' => $i // Opcional: para numerar las filas
            ];
        }
        return view('admin.candidates.create', compact('district', 'party', 'filasEnBlanco'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $district = District::find($request->input('district_id'));
        $mesa = Mesa::where('district_id', '=', $district->id)->first();

        $party = Party::findOrFail($request->input('party_id'));
        $registros = $request->input('registros');
        foreach ($registros as $registro) {
            $dni = $registro['dni'];
            $name = $registro['name'];
            $surname = $registro['surname'];
            $order = $registro['order'];

            if ($dni == null || $name == null || $surname == null) {
                $parties = $district->parties
                    ->where('code', '!=', env('BLANK_VOTES_CODE'))
                    ->where('code', '!=', env('INVALID_VOTES_CODE'));
                return view('admin.candidates.show', compact('district', 'parties'))->with('alert', [
                    'icon' => 'error',
                    'title' => 'Error!',
                    'text' => 'Candidate data is missing, please check!!.',
                ]);
            }
            $voter = Voter::where('dni', '=', $dni)->first();
            //si no existe voter, agregar datos en voter.
            if (!$voter) {
                $voter = new Voter();
                $voter->dni = $registro['dni'];
                $voter->name = $registro['name'];
                $voter->surname = $registro['surname'];
                $voter->mesa_id = $mesa->id;
                $voter->save();
            }
            $voter = Voter::where('dni', '=', $dni)->first();
            $candidate = Candidate::where('voter_id', '=', $voter->id)->first();

            if (!$candidate) {
                $candidate = new Candidate();
                $candidate->district_id = $district->id;
                $candidate->party_id = $party->id;
                $candidate->voter_id = $voter->id;
                $candidate->order = $order;
                $candidate->save();
            }
        }
        $parties = $district->parties->where('code', '!=', env('BLANK_VOTES_CODE'))
            ->where('code', '!=', env('INVALID_VOTES_CODE'));

        return view('admin.candidates.show', compact('district', 'parties'))->with('alert', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'Candidates was successfully registered!.',
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $district = District::findOrFail($id);
        //crear variable de session district_id
        session(['district_id' => $district->id]);
        $parties = $district->parties
            ->where('code', '!=', env('BLANK_VOTES_CODE'))
            ->where('code', '!=', env('INVALID_VOTES_CODE'));

        return view('admin.candidates.show', compact('district', 'parties'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $district = District::find(session('district_id'));
        $party = Party::find($id);
        //crear variable de session party_id
        session(['party_id' => $party->id]);
        $escanios = $district->escanios;

        $candidates = Candidate::where('party_id', session('party_id'))
            ->where('district_id', session('district_id'))
            ->get();
        if ($candidates->isEmpty()) {
            // Si no hay candidatos, crear $escanios+1 filas en blanco, con order del 0 a $escanios
            // Crear candidatos en blanco
            //id de voter = 52
            for ($i = 0; $i <= $escanios; $i++) {
                $candidate = new Candidate();
                $candidate->id = null;
                $candidate->voter_id = null; // Voter en blanco
                $candidate->district_id = $district->id;
                $candidate->party_id = $party->id;
                $candidate->order = $i;
                $candidate->save();
            }
            $candidates = Candidate::where('party_id', $party->id)
                ->where('district_id', $district->id)
                ->get();
        }
        // Verificar que existan escanios+1 candidatos, si no, agregar los faltantes en blanco
        $cantidadCandidatos = $candidates->count();
        if ($cantidadCandidatos < $escanios + 1) {
            for ($i = $cantidadCandidatos; $i <= $escanios; $i++) {
                $candidate = new Candidate();
                $candidate->id = null;
                $candidate->voter_id = null; // Voter en blanco
                $candidate->district_id = $district->id;
                $candidate->party_id = $party->id;
                $candidate->order = $i;
                $candidate->save();
            }
            $candidates = Candidate::where('party_id', $party->id)
                ->where('district_id', $district->id)
                ->get();
        }
        // Si hay candidatos, obtener sus nombres y apellidos desde la tabla Voter
        $candidatosConNombre = [];
        foreach ($candidates as $candidate) {
            $voter = Voter::find($candidate->voter_id);
            $candidatosConNombre[] = [
                'id' => $candidate->id,
                'voter_id' => $candidate->voter_id,
                'district_id' => $candidate->district_id,
                'party_id' => $candidate->party_id,
                'order' => $candidate->order,
                'name' => $voter ? $voter->name : '', // Obtener el nombre del votante
                'surname' => $voter ? $voter->surname : '', // Obtener el apellido del votante
                'dni' => $voter ? $voter->dni : '', // Obtener el DNI del votante
            ];
        }

        $candidates = $candidatosConNombre;
        return view('admin.candidates.edit', compact('candidates', 'party', 'district'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar votante por DNI
        $voter = Voter::where('dni', '=', $request->input('dni'))->first();
        //si el votante existe en tabla voters, verificar que no este asignado a otro candidato
        $nuevoVoter = Candidate::where('voter_id', '=', $voter ? $voter->id : 0)
            ->where('id', '!=', $request->input('candidate_id'))
            ->first();
        //si existe otro candidato con ese voter_id, mostrar error
        if ($nuevoVoter) {
            return back()->with('alert', [
                'icon' => 'error',
                'title' => 'Error!',
                'text' => 'DNI already assigned to another candidate, please check!!.',
            ]);
        }
        // si no existe el votante, crear uno nuevo y verificar que no exista mismo nombre y mismo apellido

        if (!$voter) {
            $voter = new Voter();
            $voter->mesa_id = Mesa::where('district_id', '=', $request->input('district_id'))->first()->id;
            $voter->dni = $request->input('dni');
            $voter->name = $request->input('name');
            $voter->surname = $request->input('surname');
            $voter->save();
        } else {
            //si existe, verificar que es el mismo nombre y apellido
            if ($voter->name != $request->input('name') && $voter->surname != $request->input('surname')) {
                return back()->with('alert', [
                    'icon' => 'error',
                    'title' => 'Error!',
                    'text' => 'DNI already exists with different name or surname, please check!!.',
                ]);
            }
        }
        // actualizar el candidato con el voter_id
        $candidate = Candidate::findOrFail($request->input('candidate_id'));
        $candidate->voter_id = $voter->id;
        $candidate->save();
        $district = District::find($request->session()->get('district_id'));
        $party = Party::find($request->session()->get('party_id'));
        $candidates = Candidate::where('party_id', $party->id)
            ->where('district_id', $district->id)
            ->get();

        // Si hay candidatos, obtener sus nombres y apellidos desde la tabla Voter
        $candidatosConNombre = [];
        foreach ($candidates as $candidate) {
            $voter = Voter::find($candidate->voter_id);
            $candidatosConNombre[] = [
                'id' => $candidate->id,
                'voter_id' => $candidate->voter_id,
                'district_id' => $candidate->district_id,
                'party_id' => $candidate->party_id,
                'order' => $candidate->order,
                'name' => $voter ? $voter->name : '', // Obtener el nombre del votante
                'surname' => $voter ? $voter->surname : '', // Obtener el apellido del votante
                'dni' => $voter ? $voter->dni : '', // Obtener el DNI del votante
            ];
        }
        $candidates = $candidatosConNombre;
        if ($request->input('from') == 'lista') {
            return redirect()->route('admin.candidates.obtenerRegistro', ['id' => $candidate->id, 'party_id' => $party->id])->with('alert', [
                'icon' => 'success',
                'title' => 'Success!',
                'text' => 'Candidate was successfully updated!.',
            ]);
        } else {
            return view('admin.candidates.edit', compact('candidates', 'party', 'district'))->with('alert', [
                'icon' => 'success',
                'title' => 'Success!',
                'text' => 'Candidate was successfully updated!.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function searchName(Request $request)
    {
        $dni = $request->input('dni');
        //Buscar en la tabla voters por dni
        //si existe, devolver nombre y apellido
        $voter = Voter::searchByDni($dni);
        if ($voter!= null) {
            return response()->json([
                'name' => $voter->name,
                'surname' => $voter->surname,
            ]);
        } else {
            return response()->json([
                'name' => '',
                'surname' => '',
            ]);
        }
    }

    public function obtenerRegistro($id, $party_id, Request $request)
    {
        $party = Party::find($party_id);
        $district = District::find($request->session()->get('district_id'));
        $candidate = Candidate::find($id);

        $voter = Voter::where('id', '=', $candidate->voter_id)->first();

        return view('admin.candidates.lista', compact('candidate', 'voter'));
    }

    public function printListPDF(Request $request)
    {

        //Generar PDF con la lista de candidatos de un partido en un distrito
        $district = District::find($request->input('district_id'));
        $party = Party::find($request->input('party_id'));
        $candidates = Candidate::where('party_id', $party->id)
            ->where('district_id', $district->id)
            ->orderBy('order')
            ->get();
        //Si no hay votantes con id voter_id, retornar error
        foreach ($candidates as $candidate) {
            $voter_id = $candidate->voter_id;
            if ($voter_id == null) {
                //retornar pdf en blanco con mensaje de error
                $pdf = new Fpdf();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'Error: Incomplete candidate data',0,1, 'C');
                $pdf->Ln(10);
                $pdf->SetFont('Arial', '', 12);
                $pdf->MultiCell(0, 10, 'There are candidates with incomplete data (missing voter information). Please ensure all candidates have complete voter details before generating the PDF.', 0, 'C');
                //Crear archivo candidates_error.pdf en storage/app/public y retornar la ruta
                $outputPath = storage_path('app/public/candidates_error.pdf');
                $pdf->Output('F', $outputPath);
                return response()->download($outputPath, 'candidates_error_' . $party->code . '_' . $district->code . '.pdf'); 
            }
        }

        // Crear el PDF
        $pdf = new Fpdf();
        $pdf->AliasNbPages(); // <-- IMPORTANTE

        $pdf->AddPage();
        //Logo del partido en la esquina superior izquierda
        // Encabezado, título y tabla de candidatos
        $rutaImg = public_path('storage\\images\\' . str_replace('/', '\\', $party->logo_path));
        //Verificar si el archivo existe y si es una imagen
        if (file_exists($rutaImg) && getimagesize($rutaImg)) {
            $pdf->Image($rutaImg, 10, 10,24); // Ajusta la posición y el tamaño según sea necesario
        }
        

        $pdf->Ln(0);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Candidate List',0,1, 'C');
        $pdf->cell( 0,10, $party->name . ' - ' . $district->name, 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', size: 10);
        $pdf->Cell(20, 10, 'Order', 1);
        $pdf->Cell(30, 10, 'DNI', 1);
        $pdf->Cell(60, 10, 'Name', 1);
        $pdf->Cell(60, 10, 'Surname', 1);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 10);
        foreach ($candidates as $candidate) {
            $voter = Voter::find($candidate->voter_id);
            $pdf->Cell(20, 10, $candidate->order==0 ? 'Mayor   ': $candidate->order, 1,0,'R');
            $pdf->Cell(30, 10, $voter ? $voter->dni : '', 1);
            $pdf->Cell(60, 10, $voter ? iconv('UTF-8', 'windows-1252', $voter->name) : '', 1);
            $pdf->Cell(60, 10, $voter ? iconv('UTF-8','windows-1252',$voter->surname) : '', 1);
            $pdf->Ln();
        }
        
        //Crear archivo candidates.pdf en storage/app/public y retornar la ruta
        $outputPath = storage_path('app/public/candidates.pdf');
        $pdf->Output('F', $outputPath);
        return response()->download($outputPath, 'candidates_' . $party->code . '_' . $district->code . '.pdf');
    }

}