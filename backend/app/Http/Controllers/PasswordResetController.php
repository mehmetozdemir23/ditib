<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function sendResetLink(ForgotPasswordRequest $request): JsonResponse
    {
        $token = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetUrl = route('password.reset', ['token' => $token]).'?email='.urlencode($request->email);

        Mail::to($request->email)->send(new ResetPasswordMail($resetUrl));

        return response()->json([
            'message' => 'Si cette adresse email existe dans notre système, un lien de réinitialisation a été envoyé.',
        ]);
    }

    public function showResetForm(string $token, Request $request): RedirectResponse
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $resetRecord) {
            return redirect(config('app.frontend_url').'/password/forgot?error=invalid_token');
        }

        if (! Hash::check($token, $resetRecord->token)) {
            return redirect(config('app.frontend_url').'/password/forgot?error=invalid_token');
        }

        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return redirect(config('app.frontend_url').'/password/forgot?error=token_expired');
        }

        $frontendUrl = config('app.frontend_url').'/password/reset';
        $queryString = http_build_query([
            'token' => $token,
            'email' => $request->email,
        ]);

        return redirect($frontendUrl.'?'.$queryString);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $resetRecord) {
            return response()->json([
                'message' => 'Le token de réinitialisation est invalide.',
            ], 422);
        }

        if (! Hash::check($request->token, $resetRecord->token)) {
            return response()->json([
                'message' => 'Le token de réinitialisation est invalide.',
            ], 422);
        }

        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return response()->json([
                'message' => 'Le token de réinitialisation a expiré.',
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'message' => 'Utilisateur introuvable.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'message' => 'Votre mot de passe a été réinitialisé avec succès.',
        ]);
    }
}
