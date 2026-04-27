<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'name',
        'relationship',
        'phone_number',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Relación 1 a N inversa: Un contacto de emergencia pertenece a un perfil.
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
