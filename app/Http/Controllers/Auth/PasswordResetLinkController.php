<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
        ]);

        $otp = (new Otp)->generate($request->email, 'numeric', 6);

        // send email
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Kode OTP untuk reset password');
            $message->from('ryougi4444@gmail.com');
        });

        return redirect('/otp-verification?email=' . $request->email . '&type=forgot%20password')->with('success', 'Kode reset password telah dikirim. Silahkan cek email Anda.');
    }
}
