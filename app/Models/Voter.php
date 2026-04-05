<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Voter extends Model
{
    /** @use HasFactory<\Database\Factories\ElectorFactory> */
    use HasFactory;

    protected $fillable = [
        'dni',
        'name',
        'surname',
        'date_of_birth',
        'mesa_id',
        'active',
        'photo_path'
    ];

    protected function name(): Attribute
    {
        return new Attribute(
            set: fn(string $value) => ucwords($value),
        );
    }

    protected function surname(): Attribute
    {
        return new Attribute(
            set: fn(string $value) => ucwords($value),
        );
    }

    public function parties()
    {

        return $this->hasMany(Party::class);
    }

    public function mesa()
    {

        return $this->belongsTo(Mesa::class);
    }

    //Relacionar con candidates
    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }
    //Relacionar con users
    public function user()
    {
        return $this->hasOne(User::class);
    }
    //Relacionar con photo_path
    public function photo_path(){
        return $this->photo_path;
    }
    

    //Buscar por dni
    public static function searchByDni($dni)
    {
        return self::where('dni', $dni)->first();
    }

    //Para obtener la photo del voter
    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        } else {
            return asset('photos/generic.jpg');
        }
    }
    
}
