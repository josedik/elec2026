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

    //Relacionar con votes
    public function votes()
    {
        return $this->hasMany(Voter::class);
    }

    //Relacionar con users
    public function user()
    {
        return $this->hasOne(User::class);
    }

    //Buscar por dni
    public static function searchByDni($dni)
    {
        return self::where('dni', $dni)->first();
    }
   

}
