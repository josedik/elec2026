<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\District;
use App\Models\Province;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class MuestreoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // List all districts
        $districts = District::orderBy('code')
            ->get();
        // Add column with number of samples using slovin method
        // Calculate the number of samples for each district; if the code ends in 01, accumulate the sum of districts and perform the calculation using the Slovin method
        $samplesProvince = [];
        foreach ($districts as $district) {
            $province_code = substr($district->code, 0, 4);
            if (!isset($samplesProvince[$province_code])) {
                $samplesProvince[$province_code] = 0;
            }
            $samplesProvince[$province_code] += $district->population;
        }
        $districtSamples = 1;
        foreach ($districts as $district) {
            $province_code = substr($district->code, 0, 4);
            if (substr($district->code, -2) == '01') {
                $N = $samplesProvince[$province_code];
                $e = 0.05; // margen de error del 5%
                $districtSamples = $this->slovin($N, $e);
                $district->samples = round($district->population * $districtSamples / $samplesProvince[$province_code]);
            } else {
                $district->samples = round($district->population * $districtSamples / $samplesProvince[$province_code]);
            }
        }
        return view('admin.muestreos.index', compact('districts'));
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
        // Recibir los datos del formulario y redirigir a la vista de impresion
        $district_id = $request->input('district_id');
        $samples = $request->input('samples');
        $votes = $request->input('votes');
        // llamar metodo pdf para generar el pdf
        return $this->pdf($district_id, $samples, $votes);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, int $samples)
    {
        $district = District::findOrFail($id);
        // select parties where district_id = $id for printing form to select parties
        $parties = $district->parties;
        $partidos=$parties->count();
        return view('admin.muestreos.show', compact('district', 'parties', 'samples','partidos'));
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

    public function slovin($N, $e): int
    {
        // Implementación del método Slovinsky si es necesario
        $muestra = $N / (1 + $N * $e * $e);

        return round($muestra);

    }

    public function pdf($district_id, $samples, $votes)
    {
        $from_encoding = 'UTF-8';
        $to_encoding = 'ISO-8859-1';
        $aleatorio = rand(5, 12);
        $district = District::where('id', $district_id)->first();
        $province = Province::where('code', substr($district->code, 0, 4))->first();
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Image('storage/images/logoStereo.png', 10, 8, 30, 30);

        $pdf->Cell(0, 10, 'Sheet survey by district', 0, 1, 'C');
        $pdf->Cell(0, 10, $district->name, 0, 1, 'C');
        $pdf->Ln(10);
        $w = [10, 100, 80]; // Column widths
        // Table header
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($w[0], 10, '#', 1, 0, 'C');
        $pdf->Cell($w[1], 10, 'Political parties', 1, 0, 'C');
        $pdf->Cell($w[2], 10, 'Votes', 1, 0, 'C');
        $pdf->Ln();
        $h = 6;
        $pdf->SetFont('Arial', '', 8);
        $totalparties = 0;
        $orden = 1;
        if ($district->parties->count()==0) {
            $district->parties = collect(range(1, 10))->map(function($i) {
                return (object)[
                    'order'=>$i,
                    'name' => 'party_' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'votes'=>''];
            });
        }
        foreach ($district->parties as $row) {

            $pdf->Cell($w[0], $h, $orden, 1, 0, 'R');
            $pdf->Cell($w[1], $h, iconv($from_encoding, $to_encoding, $row->name), 1, 0, );
            $pdf->Cell($w[2], $h, '  ', 1, 0, '');

            $pdf->Ln();
            $orden++;
            $totalparties++;
        }

        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, $h, 'Surveys ', 1, 0, 'R');
        $pdf->Cell(30, $h, $samples, 1, 0, 'R');$pdf->Ln();

        $pdf->Cell(50, $h, 'Political parties: ', 1, 0, 'R');
        $pdf->Cell(30, $h, $totalparties - 2, 1, 0, 'R');
        $hoy = date('d/m/Y');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Date: $hoy", 0, 1, 'R');
        $pdf->Cell(0, 10, "Every " . $aleatorio . ' people', 0, 1, 'R');
        $pdf->Ln(2);

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="sheet_province.pdf"');

        //
    }

}
