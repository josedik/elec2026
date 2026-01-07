<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\districts_has_parties;
use App\Models\Mesa;
use App\Models\Mesa_has_parties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;
use Ramsey\Uuid\Type\Integer;
use function Livewire\str;

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
            }

            return view('admin.reports.show', compact('district', 'results', 'provincia', 'totalVotes'));


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
                ->orderBy('total', 'desc')
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
            return view('admin.reports.show', compact('district', 'results', 'provincia', 'totalVotes'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
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


}
