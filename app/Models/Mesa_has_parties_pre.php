<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa_has_parties_pre extends Model
{
    protected $table = 'mesa_has_parties_pre';
    protected $fillable = [
        'mesa_id',
        'party_id',
        'votes_president',
        'votes_senatornac',
        'votes_senatorreg',
        'votes_diputies',
        'votes_andino',
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
