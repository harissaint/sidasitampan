<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginWithGoogleController extends Controller
{
    //
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if ($finduser) {
                if ($finduser->status) {
                    Auth::login($finduser);
                    return redirect()->intended('dashboard');
                } else {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Akun Anda tidak aktif. Silahkan hubungi admin!',
                    ]);
                }
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'photo' => $user->avatar,
                    'password' => encrypt('123456dummy')
                ]);

                return redirect()->route('login')->with('success', 'Anda telah didaftarkan. Menunggu persetujuan admin!');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
