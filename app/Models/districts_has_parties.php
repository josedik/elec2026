<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class districts_has_parties extends Model
{
        use HasFactory;
        protected $table = 'district_has_parties';
        protected $fillable = [
            'district_id',
            'party_id',
            'governors',
        ];

        public function voters()
    {
        return $this->belongsTo(Voter::class, 'voter_id');
    }

    public function district_has_parties()
    {
        return $this->belongsTo(districts_has_parties::class, 'district_has_parties_id');
    }


}
