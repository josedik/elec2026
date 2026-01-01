<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Province extends Model
{
    /** @use HasFactory<\Database\Factories\ProvinceFactory> */
    use HasFactory;
    
    protected $fillable = ['code','name', 'department_id'];


    protected function setNameAttribute($value)
    {
        $value = Str::lower($value);
        $this->attributes['name'] = ucwords($value);
    }
    protected static function getByDepartment($departmentId)
    {
        return self::where('department_id', $departmentId)->get();
    }

    protected function department(){
        return $this->belongsTo(Department::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    // total population of the province
    

}