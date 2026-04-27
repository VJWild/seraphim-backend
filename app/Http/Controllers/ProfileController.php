<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * VISTA PÚBLICA (SOS): Obtiene el perfil a partir del QR Slug
     * No requiere estar logueado.
     */
    public function showPublic($qr_slug)
    {
        // Buscamos el perfil por su slug e incluimos sus contactos de emergencia
        $profile = Profile::with('emergencyContacts')->where('qr_slug', $qr_slug)->firstOrFail();

        return response()->json($profile);
    }

    /**
     * Obtiene el perfil del usuario autenticado
     */
    public function show(Request $request)
    {
        $profile = $request->user()->profile()->with('emergencyContacts')->first();

        if (!$profile) {
            return response()->json(['message' => 'Perfil no encontrado.'], 404);
        }

        return response()->json($profile);
    }

    /**
     * Crea un nuevo perfil para el usuario autenticado
     */
    public function store(Request $request)
    {
        // Validamos los datos críticos
        $request->validate([
            'id_number' => 'required|unique:profiles,id_number',
            'full_name' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F,Otro',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address_text' => 'required|string',
        ]);

        $data = $request->all();

        // Generamos un identificador aleatorio de 10 caracteres para el QR
        // Es mejor que un UUID largo para que el QR sea menos denso y más rápido de escanear
        $data['qr_slug'] = Str::random(10);

        // Creamos el perfil asociado al usuario que hace la petición
        $profile = $request->user()->profile()->create($data);

        return response()->json([
            'message' => 'Perfil creado exitosamente.',
            'profile' => $profile
        ], 201);
    }

    /**
     * Actualiza el perfil del usuario autenticado
     */
    public function update(Request $request)
    {
        $profile = $request->user()->profile;

        if (!$profile) {
            return response()->json(['message' => 'Perfil no encontrado.'], 404);
        }

        // Reglas de validación para actualizar (ignorando el id_number actual si no cambia)
        $request->validate([
            'id_number' => 'sometimes|required|unique:profiles,id_number,' . $profile->id,
            'full_name' => 'sometimes|required|string',
            'gender' => 'sometimes|required|in:M,F,Otro',
            'blood_type' => 'sometimes|required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);

        $profile->update($request->all());

        return response()->json([
            'message' => 'Perfil actualizado exitosamente.',
            'profile' => $profile
        ]);
    }
}
