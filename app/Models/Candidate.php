<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{/** @use HasFactory<\Database\Factories\ElectorFactory> */
    use HasFactory;

    protected $fillable = [
        'district_id',
        'party_id',
        'voter_id',
        'order',
    ];

    //Relacionar con voters
    public function voter()
    {
        return $this->belongsTo(Voter::class);
    }
    //Relacionar con districts
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    //Relacionar con parties
    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    
}
