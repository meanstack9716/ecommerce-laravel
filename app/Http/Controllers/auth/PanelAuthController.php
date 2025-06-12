<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\VerificationCode;
use App\Constants\Constants;
use App\Mail\EmailVerification;
use App\Http\Controllers\Controller;
use App\ValidationSchemas\Schemas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PanelAuthController extends Controller
{

    public function signinPanelUser(Request $request) 
    {    
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->with('error', 'The provided credentials are incorrect.')
                ->withInput();
        }
        if (!$user->is_admin && $user->role->name != Constants::SELLER_ROLE ) {
            redirect()->back()
                ->with('error', 'Access denied. Only admin or seller can login.')
                ->withInput();
        }
        if ($user->status == Constants::STATUS_DEACTIVATED || $user->status == Constants::STATUS_ON_HOLD ) {
            redirect()->back()
                ->with('error', 'These account is not activated.')
                ->withInput();
        }
        Auth::guard('web')->login($user);
        return redirect('/dashboard');
    }

    public function logoutCurrentUser(Request $request)
    {    
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function sendEmailCodeForUser(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            session()->put('email', $email);
            return redirect()->route('password.verify-code')->with('toast', [
                'type' => 'success',
                'message' => 'If an account with the provided email exists, a password reset link has been sent.'
            ]);
        }
        VerificationCode::where('email', $email)->delete();

        $code = rand(100000, 999999);

        $verificationCode = VerificationCode::create([
            'email' => $email,
            'code' => $code,
            'is_used' => false
        ]);

        Mail::to($email)->send(new EmailVerification($verificationCode));
        session()->put('email', $email);
        return redirect()->route('password.verify-code')->with('toast', [
            'type' => 'success',
            'message' => 'If an account with the provided email exists, a password reset link has been sent.'
        ]);
    }

    public function showVerifyCodeForm()
    {
        $email = session('email');
        
        if (!$email) {
            return redirect('/forgot-password');
        }
        return view('auth.verify-reset-code', ['email' => $email]);
    }

    public function resendVerificationCode(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            session()->put('email', $email);
            return back()->with('toast', [
                'type' => 'success',
                'message' => 'A new Verification code sent successfully!'
            ]);
        }
    
        VerificationCode::where('email', $email)->delete();

        $code = rand(100000, 999999);

        $verificationCode = VerificationCode::create([
            'email' => $email,
            'code' => $code,
            'is_used' => false
        ]);

        session()->put('email', $email);
        Mail::to($email)->send(new EmailVerification($verificationCode));
        return back()->with('toast', [
            'type' => 'success',
            'message' => 'A new Verification code sent successfully!'
        ]);
    }

    public function verifyEmailCode(Request $request)
    {
        $email = $request->email;
        $code = $request->code;
        $expiryMinutes = Constants::EMAIL_VERIFICATION_CODE_EXPIRY_MINUTES;

        $verification = VerificationCode::where('email', $email)
        ->where('code', (int) $code)
        ->where('is_used', false)
        ->first();

        if (!$verification) {
            return redirect()->back()
                ->with('error', 'Invalid or expired verification code.')
                ->withInput();
        }

        if ($verification->created_at->addMinutes($expiryMinutes)->lt(now())) {
            $verification->delete();
            return redirect()->back()
            ->with('error', 'Invalid or expired verification code.')
            ->withInput();        
        }
        $verification->delete();
        session()->put('email', $email);
        session()->put('code', $code);
        return redirect()->route('password.reset')->with('toast', [
            'type' => 'success',
            'message' => 'Your code has been verified!'
        ]);
    }

    public function showResetPasswordForm()
    {
        $email = session('email');
        $code = session('code');
        
        if (!$email || !$code) {
            return redirect('/forgot-password');
        }
        return view('auth.reset-password', ['email' => $email, 'code' => $code]);
    }

    public function resetUserPassword(Request $request)
    {
        $email = $request->email;
        $code = $request->code;
        $password = $request->password;
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($request->password);
        $user->tokens()->delete();
        $user->save();

        return redirect('/login');
    }
}
