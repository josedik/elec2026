<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\Mesa_has_parties_pre;
use App\Models\Party;
use Codedge\Fpdf\Fpdf\Fpdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use function Laravel\Prompts\select;

class VotosController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.votos.index')->only(['index']);
        $this->middleware('can:admin.votos.create')->only(['create', 'store']);
        $this->middleware('can:admin.votos.edit')->only(['edit', 'update']);
        $this->middleware('can:admin.votos.destroy')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $code = 0)
    {
        $code = (int) $request->input('code');
        if ($code > 0) {
            $mesa = Mesa::where('code', 'LIKE', '%' . $code . '%')->first();
            //Recuperar los votos de la mesa encontrada, estan en la tabla mesa_has_parties_pre, con el id de la mesa encontrada.
            if (!$mesa) {
                return redirect()->route('admin.votos.index')->with('error', 'Mesa no encontrada.');
            }
            $votos = Mesa_has_parties_pre::join('parties', 'mesa_has_parties_pre.party_id', '=', 'parties.id')
                ->where('mesa_has_parties_pre.mesa_id', $mesa->id)
                ->orderBy('parties.order')
                ->get();
            //Recuperar los partidos ordenados por el campo order.
            $parties = Party::orderBy('order')->get();

        } else {
            $mesa = null;
            $parties = null;
            $votos = null;
        }
        //dd($mesa, $parties, $votos);
        return view('admin.votos.index', compact('mesa', 'parties', 'votos'));
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
        //return $request->all();

        $mesa = Mesa::find($request->input('mesa_id'));
        $votos = $request->input('votes');
        //los datos que vienen de la vista se guardan en la tabla mesa_has_parties_pre.
        $totalPresident = 0;
        $totalSenatorNac = 0;
        $totalSenatorReg = 0;
        $totaldiputies = 0;
        $totalParlamenentAndino = 0;
        $hayvotos = 0;
        $emitidospre = $request->input('emitidos');
        $votantes = 0;
        if ($emitidospre > 0 && $emitidospre <= $mesa->electors) {
            $mesa->emitidospre = $emitidospre;
            $mesa->save();
            $votantes = min($emitidospre, $mesa->electors);    # code...
        } else {
            if ($emitidospre <= 0) {
                return redirect()->route('admin.votos.index', ['code' => $mesa->code])->with('error', 'Number of votes equal to zero, not registered.. Please verify.');
            }

            return redirect()->route('admin.votos.index', ['code' => $mesa->code])->with('error', 'The number of votes cast cannot exceed the number of voters. Please verify.');
        }

        foreach ($votos as $party_id => $cantidad) {
            $totalPresident += $cantidad['votes_president'] ?? 0;
            $totalSenatorNac += $cantidad['votes_senatornac'] ?? 0;
            $totalSenatorReg += $cantidad['votes_senatorreg'] ?? 0;
            $totaldiputies += $cantidad['votes_diputies'] ?? 0;
            $totalParlamenentAndino += $cantidad['votes_andino'] ?? 0;
        }
        if ($totalPresident == 0 && $totalSenatorNac == 0 && $totalSenatorReg == 0 && $totaldiputies == 0 && $totalParlamenentAndino == 0) {

            return redirect()->route('admin.votos.index', ['code' => $mesa->code])->with('error', 'There is missing data to register them, please check.');
        }

        if ($totalPresident != $votantes || $totalSenatorNac != $votantes || $totalSenatorReg != $votantes || $totaldiputies != $votantes || $totalParlamenentAndino != $votantes) {

            return redirect()->route('admin.votos.index', ['code' => $mesa->code])->with('error', 'The number of votes cast does not match the total number of votes recorded. Please verify.');
        }


        foreach ($votos as $party_id => $cantidad) {
            Mesa_has_parties_pre::updateOrCreate(
                ['mesa_id' => $mesa->id, 'party_id' => $party_id],
                [
                    'votes_president' => $cantidad['votes_president'] ?? 0,
                    'votes_senatornac' => $cantidad['votes_senatornac'] ?? 0,
                    'votes_senatorreg' => $cantidad['votes_senatorreg'] ?? 0,
                    'votes_diputies' => $cantidad['votes_diputies'] ?? 0,
                    'votes_andino' => $cantidad['votes_andino'] ?? 0
                ]
            );
        }


        return redirect()->route('admin.votos.index', ['code' => $mesa->code])->with('success', 'The votes have been successfully saved.');

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function verVotos(Request $request)
    {
        //Recuperar el cargo a mostrar, viene de la vista, es un select con las opciones: president, senatornac, senatorreg, diputies, andino.

        return view('admin.votos.menuVotos');


    }

    public function verResultados($cargo)
    {
        if ($cargo == '') {
            return [];
        }

        //crear variables de session para guardar los resultados de la consulta, con el cargo seleccionado, para mostrarlo en la vista.
        $campo = 'votes_' . $cargo;
        $raw = 'SUM(' . $campo . ') as total_' . $cargo;
        if ($cargo == 'printSurvey') {
            //Solo imprimir los nombres de los partidos, no incluir los votos en blanco ni nulos
            $raw = 'SUM(0) as total_' . $cargo;
        }

        $votos = Mesa_has_parties_pre::join('parties', 'mesa_has_parties_pre.party_id', '=', 'parties.id')
            ->groupBy('mesa_has_parties_pre.party_id')
            ->orderBy('total_' . $cargo, 'desc')
            ->select(
                'mesa_has_parties_pre.party_id',
                DB::raw($raw),
                'parties.name'
            )
            ->get()
        ;

        //Crear pdf con nombre del cargo y los resultados de la consulta, con el cargo seleccionado, para mostrarlo en la vista.
        $this->verPDF($votos, $cargo);

        return view('admin.votos.verVotos', compact('votos', 'cargo'));
    }

    public function verPDF($votos, $cargo)
    {
        $campo = 'total_' . $cargo;
        $msg = match ($cargo) {
            'president' => 'President',
            'senatornac' => 'National Senator',
            'senatorreg' => 'Regional Senator',
            'diputies' => 'Diputies',
            'andino' => 'Andean Parliamentarian',
            'printSurvey' => 'Survey',
            default => 'Not found',
        };
        $n = 1;
        $blancos = 0;
        $nulos = 0;
        $emitidos = 0;
        $validos = 0;
        $blankCode = env('BLANK_VOTES_CODE');
        $invalidCode = env('INVALID_VOTES_CODE');
        foreach ($votos as $item) {
            $emitidos += $item->$campo;
            if (strtoupper($item->name) == 'BLANK') {
                $blancos = $item->$campo;
                continue;
            } elseif (strtoupper($item->name) == 'INVALID') {
                $nulos = $item->$campo;
                continue;
            }
            $validos += $item->$campo;
        }

        $pdf = new Fpdf();
        $pdf->AliasNbPages(); // <-- IMPORTANTE

        $pdf->AddPage();
        $h = 12;  //height cell
        $rutaImg = public_path('storage\\' . str_replace('/', '\\', 'images/logo.png'));
        //Verificar si el archivo existe y si es una imagen
        if (file_exists($rutaImg) && getimagesize($rutaImg)) {
            $pdf->Image($rutaImg, 10, 10, 24); // Ajusta la posición y el tamaño según sea necesario
        }
        $pdf->Ln(0);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Final results: ' . $msg, 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->cell(0, 10, date('d:M:Y  / H:i:s'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(10, $h, '#', 1, 0, 'C', true);
        $pdf->Cell(100, $h, 'Party', 1, 0, 'C', true);
        $pdf->Cell(20, $h, 'Logo', 1, 0, 'C', true);
        $pdf->Cell(20, $h, 'Votes', 1, 0, 'C', true);
        $pdf->Cell(20, $h, 'Percent', 1, 1, 'C', true); // 1,1 significa salto de línea
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetFillColor(255, 255, 255);

        $order = 1;
        foreach ($votos as $result) {
            if ($result->party->code == $invalidCode || $result->party->code == $blankCode) {
                continue;
            }
            $pdf->Cell(10, $h, $order++, 1, 0, 'R', true);
            $pdf->Cell(100, $h, iconv('UTF-8', 'windows-1252', $result->party->name), 1, 0, 'L', true);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            // Dibujar un borde de celda (opcional)
            $pdf->Cell(20, $h, '', 1, 0, 'C', true);
            // Insertar la imagen en la posición de la celda
            //$pdf->Image( asset('storage/' . $result->party->logo_path), x, y, ancho, alto);
            $file = $result->party->logo_path;
            if ($file == null || $file == '') {
                $file = 'logo.png';
            }
            $pdf->Image(asset('storage/images/' . $file), $x + 6, $y + 2, $h - 4, $h - 4);

            // Mover el cursor a la siguiente celda
            $pdf->SetX($x + 20);
            $voto = ($result->$campo > 0 || $emitidos > 0) ? $result->$campo : '';
            $pdf->Cell(20, $h, $voto, 1, 0, 'R', true);
            if ($voto == '') {
                $percent = '';
            } else {
                $percent = number_format($voto * 100 / $validos, 3, '.', ',');
            }
            $pdf->Cell(20, $h, $percent, 1, 1, 'C', true); // 1,1 significa salto de línea

        }
        foreach ($votos as $result) {
            if ($result->party->code == $invalidCode || $result->party->code == $blankCode) {
                # code..

                $pdf->Cell(10, $h, $order++, 1, 0, 'R', true);
                $pdf->Cell(100, $h, iconv('UTF-8', 'windows-1252', $result->party->name), 1, 0, 'L', true);
                $pdf->Cell(20, $h, '', 1, 0, 'R', true);
                $voto = $result->$campo == 0 ? '' : $result->$campo;
                $pdf->Cell(20, $h, $voto, 1, 0, 'R', true);
                if ($result->$campo == 0) {
                    $percent = '';
                } else {
                    $percent = number_format($voto * 100 / $emitidos, 3, '.', ',');
                }

                $pdf->Cell(20, $h, $percent, 1, 1, 'C', true); // 1,1 significa salto de línea
            }
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(200, 200, 200);

        $pdf->Cell(10, $h, '', 1, 0, 'L', true);
        $pdf->Cell(100, $h, 'Totales', 1, 0, 'L', true);
        $emitidos = $emitidos == 0 ? '' : $emitidos;
        $pdf->Cell(20, $h, $emitidos, 1, 0, 'R', true);
        $validos = $validos == 0 ? '' : $validos;
        $pdf->Cell(20, $h, $validos, 1, 0, 'R', true);
        $pdf->Cell(20, $h, 100, 1, 1, 'C', true); // 1,1 significa salto de línea
        if ($cargo == 'printSurvey') {
            $aleatorio = rand(3, 13);
            $samples = '';
            $totalparties = count($votos);
            $pdf->SetFont('Arial', 'B', 10);
            //$pdf->Cell(50, $h, 'Surveys ', 1, 0, 'R');
            //$pdf->Cell(30, $h, $samples, 1, 0, 'R');
            $pdf->Ln(5);

            $pdf->Cell(50, $h, 'Political parties: ', 1, 0, 'R');
            $pdf->Cell(30, $h, $totalparties - 2, 1, 0, 'R');

            $pdf->Cell(0, 10, "Every " . $aleatorio . ' people', 0, 1, 'R');
        }

        $outputPath = storage_path('app/public/' . $cargo . '.pdf');
        $pdf->Output('F', $outputPath);
        return;

    }
}
