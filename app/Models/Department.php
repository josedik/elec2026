<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;

    protected $fillable = ['name','code'];

    protected function setNameAttribute($value)
    {
        $value = Str::lower($value);
        $this->attributes['name'] = ucwords($value);
    }

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }
    
}
