<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (
            auth()->attempt(
                $request->safe()->only('email', 'password'),
                $request->safe()->boolean('remember_me')
            )
        ) {
            $request->session()->regenerate();

            $user = auth()->user();

            return response()->json([
                'message' => 'Connexion réussie',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
            ]);
        }

        return response()->json([
            'message' => 'Les identifiants fournis sont incorrects.',
            'errors' => [
                'email' => ['Les identifiants fournis sont incorrects.'],
            ],
        ], 422);
    }

    public function logout(Request $request): JsonResponse
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }
}
