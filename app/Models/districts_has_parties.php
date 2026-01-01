<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class districts_has_parties extends Model
{
        use HasFactory;
        protected $table = 'districts_has_parties';
        protected $fillable = [
            'district_id',
            'party_id',
            'votes',
        ];

}
