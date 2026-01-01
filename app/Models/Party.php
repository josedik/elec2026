<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Party extends Model
{
    use HasFactory;

     protected $fillable = [
        'code',
        'name',
        'acronym',
        'voter_id',
        'logo_path',
        'active',
    ];

    protected function name(): Attribute
    {
        return new Attribute(
            set: fn(string $value) => strtoupper($value),
        );
    }

    protected function acronym(): Attribute
    {
        return new Attribute(
            set: fn(string $value) => strtoupper($value),
        );
    }
    public function voter(){

        return $this->belongsTo(Voter::class);
    }
    
    public function districts()
    {
        return $this->belongsToMany(District::class, 'district_has_parties')
                    ->withPivot('votes')
                    ->withTimestamps();
    }

    public function mesas(){
        return $this->belongsToMany(Mesa::class, 'mesa_has_parties')
                    ->withPivot('votes_province')
                    ->withPivot('votes_district')
                    ->withTimestamps();
    }

    
}
