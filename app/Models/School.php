<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class School extends Model
{
    /** @use HasFactory<\Database\Factories\DistrictFactory> */
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'address',
        'district_id',
        'tables',
        'voters',

    ];

    protected function name(): Attribute
    {
        return new Attribute(
            set: fn(string $value) => ucwords($value),
        );
    }
    public function district()
    {
        return $this->belongsTo(District::class);

    }

    public function mesas()
    {
        return $this->hasMany(Mesa::class);
    }

    

}
