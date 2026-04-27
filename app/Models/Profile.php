<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_number',
        'full_name',
        'birth_date',
        'gender',
        'weight',
        'height',
        'blood_type',
        'allergies',
        'medical_conditions',
        'current_medications',
        'organ_donor',
        'health_insurance',
        'address_text',
        'latitude',
        'longitude',
        'qr_slug',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'organ_donor' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Accesores (Atributos calculados dinámicamente)
     * Permite llamar a $profile->age para obtener la edad exacta.
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['birth_date'])->age;
    }

    /**
     * Relación 1 a 1 inversa: Un perfil pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación 1 a N: Un perfil tiene muchos contactos de emergencia.
     */
    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContact::class);
    }
}
