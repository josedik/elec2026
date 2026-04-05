<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'code',
        'electors',
        'dnii',
        'dnif',
        'school_id',
        'district_id',
    ];

    public function school()
    {

        return $this->belongsTo(School::class);
    }
    public function voters()
    {
        return $this->hasMany(Voter::class);
    }

    public function parties()
    {
        return $this->belongsToMany(Party::class, 'mesa_has_parties')
            ->withPivot('votes_province')
            ->withPivot('votes_district')
            ->withTimestamps();
    }

    public function mesaHasParties()
    {
        return $this->hasMany(Mesa_has_parties::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
