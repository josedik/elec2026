<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa_has_parties extends Model
{
    protected $table = 'mesa_has_parties';
    protected $fillable = [
        'mesa_id',
        'party_id',
        'votes_province',
        'votes_district',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id');
    }

    

}
