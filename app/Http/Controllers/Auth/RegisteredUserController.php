<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Ichtrojan\Otp\Otp;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas'
        ]);

        event(new Registered($user));

        $otp = (new Otp)->generate($request->email, 'numeric', 6);

        // send email
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Kode OTP');
            $message->from('ryougi4444@gmail.com');
        });

        return redirect('/otp-verification?email=' . $request->email . '&type=register')->with('success', 'Akun berhasil dibuat. Silahkan cek email untuk verifikasi kode OTP');
    }

    public function otpVerification(Request $request)
    {
        return view('auth.otp-verification', ['type' => $request->type]);
    }

    public function otpValidation(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $otp = (new Otp)->validate($request->email, $request->otp);

        if (!$otp->status) {
            return back()->with('error', 'Kode OTP tidak valid');
        }

        if ($request->type == 'forgot password') {
            return redirect('/reset-password/token?=' . Str::random(60));
        }

        $user = User::where('email', $request->email)->first();
        $user->email_verified_at = now();
        $user->save();

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->with('success', 'Akun berhasil diverifikasi');
    }
}
