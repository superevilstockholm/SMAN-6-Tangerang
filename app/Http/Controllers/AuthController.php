<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

// Models
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request): View | RedirectResponse
    {
        try {
            if ($request->user()) {
                return redirect(route('dashboard.' . $request->user()->role->value . '.index'));
            }
            if ($request->isMethod('post')) {
                $validated = $request->validate([
                    'email' => 'required|email|max:255',
                    'password' => 'required|max:255'
                ], [
                    'email.required' => 'Kolom email wajib di isi.',
                    'email.email' => 'Format email tidak sesuai.',
                    'email.max' => 'Panjang email maksimal :max karakter.',
                    'password.required' => 'Kolom password wajib di isi.',
                    'password.max' => 'Panjang password maksimal :max karakter.',
                ]);
                $user = User::where('email', $validated['email'])->first();
                if (!$user || !Hash::check($validated['password'], $user->password)) {
                    return back()->withErrors('Email atau password salah.')->withInput()->withoutCookie('auth-token');
                }
                // Revoke old tokens
                $user->tokens()->delete();
                // Create new token
                $tokenResult = $user->createToken('auth-token');
                $token = $tokenResult->accessToken;
                $token->expires_at = Carbon::now()->addDays(7); // 7 days
                $token->save();
                $plainToken = $tokenResult->plainTextToken;
                return redirect(route('dashboard.' . $user->role->value . '.index'))->cookie(
                    'auth-token', // name
                    $plainToken, // value
                    60 * 24 * 7, // expiration in minutes - 7 days
                    '/', // path
                    null, // domain
                    false, // secure
                    true, // httponly
                );
            }
            return view('pages.auth.login', [
                'meta' =>[
                    'showNavbar' => false,
                    'showFooter' => false,
                ]
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput()->withoutCookie('auth-token');
        } catch (Throwable $e) {
            Log::error('Login error', [
                'exception' => $e,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => [
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'email' => $request->input('email'),
                ],
            ]);
            return back()->withErrors('Terjadi kesalahan.')->withInput()->withoutCookie('auth-token');
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();
            // Check if token exists
            if (!empty($user->tokens)) {
                // Revoke token
                $user->tokens()->delete();
            }
            return redirect(route('login'))->with('success', 'Logout berhasil.')->withoutCookie('auth-token');
        } catch (Throwable $e) {
            Log::error('Login error', [
                'exception' => $e,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => [
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'email' => $request->input('email'),
                ],
            ]);
            return redirect(route('login'))->withErrors("Terjadi kesalahan.")->withoutCookie('auth-token');
        }
    }
}
