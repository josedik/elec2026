<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\District;
use App\Models\districts_has_parties;
use App\Models\Mesa;
use App\Models\Mesa_has_parties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;

use function PHPUnit\Framework\isEmpty;

class ReportsController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $districts = District::orderBy('code')
            ->get();
        return view('admin.reports.index', compact('districts'));
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $district = District::findOrFail($id);
        if (!$district) {
            return redirect()->route('admin.reports.index')
                ->with('alert', [
                    'icon' => 'error',
                    'title' => 'Error!',
                    'text' => 'The district was not found!.',
                ]);
        }
        $totalVotes = 0;
        $prefijo = substr($district->code, 0, 4);
        $sufijo = substr($district->code, -2);
        $provincia = ($sufijo == '01') ? 1 : 0;
        if ($provincia) {
            // Es provincia, seleccionar mesas de provincia con code igual a prefijo
            // District está relacionado con province_id
            // Mesa está relacionado con district_id
            $mesas = Mesa::whereHas('district', function ($query) use ($prefijo) {
                $query->where('code', 'like', $prefijo . '%');
            })->get();

            $results = Mesa_has_parties::select(
                'party_id',
                DB::raw('SUM(votes_province) as total')
            )
                ->orderBy('total', 'desc')
                ->whereIn('mesa_id', $mesas->pluck('id'))
                ->groupBy('party_id')
                ->with('party')
                ->get();
            $totalVotes = $results->sum('total');
            $votes = [];
            foreach ($results as $result) {
                $votes[$result->party_id] = $result->total;
            }
            $seatAllocation = $this->dhondt($votes, $district->escanios);
            foreach ($results as $result) {
                $result->allocated_seats = $seatAllocation[$result->party_id] ?? 0;
                //Actualizar governors en districts_has_parties
                districts_has_parties::updateOrCreate(
                    ['district_id' => $district->id, 'party_id' => $result->party_id],
                    ['governors' => $result->allocated_seats]
                );
            }
            $pdfBase64 = $this->pdf($results, $district);


            return view('admin.reports.show', compact('district', 'results', 'provincia', 'totalVotes', 'pdfBase64'));


        } else {
            $results = Mesa_has_parties::select(
                'party_id',
                DB::raw('SUM(votes_district) as total')
            )
                ->whereIn('mesa_id', function ($query) use ($district) {
                    $query->select('id')
                        ->from('mesas')
                        ->where('district_id', $district->id);
                })
                ->orderBy('total', 'desc')
                ->groupBy('party_id')
                ->with('party')
                ->get();
            $totalVotes = $results->sum('total');
            $votes = [];
            $votosValidos = $totalVotes;
            foreach ($results as $result) {
                if ($result->party_code == env('BLANK_VOTES_CODE') || $result->party_code == env('INVALID_VOTES_CODE')) {
                    continue;
                }
                $votes[$result->party_id] = $result->total;

            }
            $seatAllocation = $this->dhondt($votes, $district->escanios);
            foreach ($results as $result) {
                $result->allocated_seats = $seatAllocation[$result->party_id] ?? 0;
            }
            //Crear archivo pdf, en forma de tabla, head: order,party,logo,Votes,Gobernors,%, en carpeta: public/storage/. Para visualizarlo en la view mediante un modal.

            $pdfBase64 = $this->pdf($results, $district);

            return view('admin.reports.show', compact('district', 'results', 'provincia', 'totalVotes', 'pdfBase64'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $district = District::findOrFail($id);
        $district_id = $district->id;
        $mensaje = " ";

        //Seleccionar de districts_has_parties governors > 0, ordenados dsc
        //con ellos se formará la lista de candidatos electos.
        $dhps = districts_has_parties::where('district_id', $district->id)
            ->where('governors', '>', 0)
            ->orderBy('governors', 'desc')
            ->get();
        $lista = [];
        $cuantos = 0;
        //seleccionar de tabla candidates
        foreach ($dhps as $key => $dhp) {
            # code...
            $candidates = Candidate::where('district_id', $district->id)
                ->where('party_id', $dhp->party_id)
                ->where('order', '<=', $dhp->governors)
                ->get();
            foreach ($candidates as $candidate) {
                if (empty($lista)) {
                    $lista[] = $candidate;
                    $cuantos++;
                } else {
                    if ($candidate->order > 0) {
                        $lista[] = $candidate;
                        $cuantos++;
                    }
                }
            }
        }
        if ($cuantos != $district->escanios + 1) {
            $mensaje = "List incomplete or does not exist, please verify!!";
        }
        //Crear pdf concejo
        $this->concejoPDF($lista, $district);


        return view('admin.reports.edit', compact('lista', 'district', 'mensaje'));



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

    public function dhondt($votes, $seats)
    {
        if (empty($votes) || $seats <= 0) {
            return [];
        }

        $parties = [];
        foreach ($votes as $party => $voteCount) {
            $parties[$party] = [];
            for ($i = 0; $i < $seats; $i++) {
                $parties[$party][] = $voteCount / ($i + 1);
            }
        }

        $allQuotients = [];
        foreach ($parties as $party => $quotients) {
            foreach ($quotients as $quotient) {
                $allQuotients[] = ['party' => $party, 'quotient' => $quotient];
            }
        }

        usort($allQuotients, function ($a, $b) {
            return $b['quotient'] <=> $a['quotient'];
        });

        $allocatedSeats = [];
        for ($i = 0; $i < $seats; $i++) {
            $allocatedSeats[] = $allQuotients[$i]['party'];
        }

        $seatCount = array_count_values($allocatedSeats);
        return $seatCount;
    }

    public function pdf($results, District $district)
    {
        $blankVotes = $this->blankVotes($results) ?? 0;
        $invalidVotes = $this->invalidVotes($results) ?? 0;
        $totalVotes = $this->totalVotes($results) ?? 0;
        $validVotes = $totalVotes - $blankVotes - $invalidVotes;

        $pdf = new Fpdf();
        $pdf->AliasNbPages(); // <-- IMPORTANTE

        $pdf->AddPage();
        $h = 12;  //height cell
        $rutaImg = public_path('storage\\' . str_replace('/', '\\', 'images/logoStereo.png'));
        //Verificar si el archivo existe y si es una imagen
        if (file_exists($rutaImg) && getimagesize($rutaImg)) {
            $pdf->Image($rutaImg, 10, 10, 24); // Ajusta la posición y el tamaño según sea necesario
        }


        $pdf->Ln(0);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Final results', 0, 1, 'C');
        $pdf->cell(0, 10, $district->name, 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->cell(0, 10, date('H:i:s'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(10, $h, '#', 1, 0, 'C', true);
        $pdf->Cell(100, $h, 'Party', 1, 0, 'C', true);
        $pdf->Cell(20, $h, 'Logo', 1, 0, 'C', true);
        $pdf->Cell(20, $h, 'Votes', 1, 0, 'C', true);
        $pdf->Cell(20, $h, 'Governors', 1, 0, 'C', true);
        $pdf->Cell(20, $h, 'Percent', 1, 1, 'C', true); // 1,1 significa salto de línea
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetFillColor(255, 255, 255);

        $order = 1;
        foreach ($results as $result) {
            if ($result->party->code == env('INVALID_VOTES_CODE') || $result->party->code == env('BLANK_VOTES_CODE')) {
                # code..
                continue;
            }
            if ($result->allocated_seats > 0) {
                # code...
                $pdf->SetFillColor(193, 229, 252);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }
            $pdf->Cell(10, $h, $order++, 1, 0, 'R', true);
            $pdf->Cell(100, $h, iconv('UTF-8', 'windows-1252', $result->party->name), 1, 0, 'L', true);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            // Dibujar un borde de celda (opcional)
            $pdf->Cell(20, $h, '', 1, 0, 'C', true);
            // Insertar la imagen en la posición de la celda
            //$pdf->Image( asset('storage/' . $result->party->logo_path), x, y, ancho, alto);
            $pdf->Image(asset('storage/images/' . $result->party->logo_path), $x + 6, $y + 2, $h - 4, $h - 4);
            // Mover el cursor a la siguiente celda
            $pdf->SetX($x + 20);

            $pdf->Cell(20, $h, $result->total, 1, 0, 'R', true);
            $pdf->Cell(20, $h, $result->allocated_seats, 1, 0, 'C', true);
            $percent = number_format($result->total * 100 / $validVotes, 3, '.', ',');
            $pdf->Cell(20, $h, $percent, 1, 1, 'C', true); // 1,1 significa salto de línea
        }
        foreach ($results as $result) {
            if ($result->party->code == env('INVALID_VOTES_CODE') || $result->party->code == env('BLANK_VOTES_CODE')) {
                # code..
                if ($result->allocated_seats > 0) {
                    # code...
                    $pdf->SetFillColor(193, 229, 252);
                } else {
                    $pdf->SetFillColor(255, 255, 255);
                }
                $pdf->Cell(10, $h, $order++, 1, 0, 'R', true);
                $pdf->Cell(100, $h, iconv('UTF-8', 'windows-1252', $result->party->name), 1, 0, 'L', true);
                $pdf->Cell(20, $h, $result->total, 1, 0, 'R', true);
                $pdf->Cell(20, $h, '', 1, 0, 'R', true);
                $pdf->Cell(20, $h, '', 1, 0, 'C', true);
                $percent = number_format($result->total * 100 / $totalVotes, 3, '.', ',');
                $pdf->Cell(20, $h, $percent, 1, 1, 'C', true); // 1,1 significa salto de línea
            }
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(200, 200, 200);

        $pdf->Cell(10, $h, '', 1, 0, 'L', true);
        $pdf->Cell(100, $h, 'Totales', 1, 0, 'L', true);
        $pdf->Cell(20, $h, $totalVotes, 1, 0, 'R', true);
        $pdf->Cell(20, $h, $validVotes, 1, 0, 'R', true);
        $pdf->Cell(20, $h, $district->escanios, 1, 0, 'R', true);
        $pdf->Cell(20, $h, 100, 1, 1, 'C', true); // 1,1 significa salto de línea

        $outputPath = storage_path('app/public/lista.pdf');
        $pdf->Output('F', $outputPath);
        return response()->download($outputPath, 'lista_' . $district->code . '.pdf');
    }

    private function blankVotes($results)
    {
        $blank = 0;
        foreach ($results as $result) {
            # code...
            if ($result->party->code == env('BLANK_VOTES_CODE')) {
                $blank += $result->total;
            }
        }
        return $blank;

    }

    private function invalidVotes($results)
    {
        $invalid = 0;
        foreach ($results as $result) {
            # code...
            if ($result->party->code == env('INVALID_VOTES_CODE')) {
                $invalid += $result->total;
            }
        }
        return $invalid;
    }

    private function totalVotes($results)
    {
        $total = 0;
        foreach ($results as $result) {
            # code...
            $total += $result->total;
        }
        return $total;
    }

    private function concejoPDF($lista, $district)
    {
        //Crear pdf con la lista de candidatos electos, en forma de tabla, con su foto y nombre debajo, en carpeta: public/storage/. Para visualizarlo en la view mediante un modal.

        // 1. Obtener imágenes desde candidates->voter->photo_path, y almacenarlas en un array
        $fotos = [];
        $nombres = [];
        foreach ($lista as $candidate) {
            //Hacer una lista de fotos. Si el candidato no tiene foto, usar una imagen por defecto. La imagen está en candidates->voter->photo_path, si no existe, usar 'storage/photos/generic.jpg'. Verificar que el archivo exista antes de agregarlo a la lista de fotos.
            if ($candidate->voter->photo_path == null) {
                $fotos[] = 'storage/photos/generic.jpg';
            } else {
                 if (file_exists(public_path('storage/photos/' . $candidate->voter->photo_path))) {
                    $fotos[] = 'storage/photos/' . $candidate->voter->photo_path;
                } else {
                    $fotos[] = 'storage/photos/generic.jpg';
                }
            }
            

            $nombres[] = $candidate->voter->name . ' ' . $candidate->voter->surname;
        }
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->AliasNbPages(); // <-- IMPORTANTE
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Elected Council ' . $district->name, 0, 1, 'C');
        $pdf->Ln(10);

        // 2. Configuracion de la matriz
        $anchoFoto = 44; // 4cm en mm
        $altoFoto = 44;  // 4cm en mm
        $columnas = 3;
        $xInicial = 20;  // Margen izquierdo
        $yInicial = 30;  // Margen superior
        $espacio = 10;   // Espacio entre fotos

        $i = 0;
        foreach ($fotos as $foto) {
            // Calcular posicion
            $columna = $i % $columnas;
            $fila = floor($i / $columnas);
            $x = $xInicial + ($columna * ($anchoFoto + $espacio));
            $y = $yInicial + ($fila * ($altoFoto + $espacio + 10)); // +10 para espacio de texto
            // Insertar imagen si existe, sino insertar una imagen por defecto 
            $pdf->Rect($x, $y, $anchoFoto, $altoFoto); // Marco

            if (file_exists($foto)) {
                $pdf->Image($foto, $x + 2, $y + 2, $anchoFoto - 4, $altoFoto - 4);
            }


            // Añadir texto debajo (opcional)
            $pdf->SetXY($x, $y + $altoFoto + 2);
            $pdf->SetFont('Arial', 'B', 6);

            $pdf->Cell($anchoFoto, 10, iconv('UTF-8', 'windows-1252', $nombres[$i]), 0, 0, 'C');
            $pdf->SetFont('Arial', 'B', 12);

            if ($fila == 0. && $columna == 0) {
                $pdf->Ln($altoFoto + $espacio + 10);
            }

            $i++;
        }
        $outputPath = storage_path('app/public/concejo.pdf');
        $pdf->Output('F', $outputPath);
        return response()->download($outputPath, 'concejo_' . $district->code . '.pdf');

    }



    private function hayFoto($url)
    {
        if (file_exists($url)) {
            return true;
        } else {
            return false;
        }

    }
}
