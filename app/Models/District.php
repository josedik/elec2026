<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class District extends Model
{
    /** @use HasFactory<\Database\Factories\DistrictFactory> */
    use HasFactory;
    
    protected $fillable = [
        'code','name','population','area','province_id'
    ];

    protected function setNameAttribute($value)
    {
        $value = Str::lower($value);
        $this->attributes['name'] = ucwords($value);
    }

    protected function schools()
    {
        return $this->hasMany(School::class);
    }
    
    public function mesas()
    {
        return $this->hasMany(Mesa::class);
    }
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function parties()
    {
        return $this->belongsToMany(Party::class, 'district_has_parties')
                    ->withPivot('votes')
                    ->withTimestamps();
    }
    
    public function getTotalPopulationAttribute()
    {
        return $this->districts()->sum('population');
    }
}
