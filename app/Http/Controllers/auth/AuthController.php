<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\VerificationCode;
use App\Constants\Constants;
use App\Mail\EmailVerification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    private function sendOtpToEmail($email)
    {
        VerificationCode::where('email', $email)->delete();

        $code = rand(100000, 999999);

        $verificationCode = VerificationCode::create([
            'email' => $email,
            'code' => $code,
            'is_used' => false
        ]);

        Mail::to($email)->send(new EmailVerification($verificationCode));
    }

    public function registerUser(Request $request) 
    {
        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $this->sendOtpToEmail($request->email);

        return response()->json([
            'message' => 'User registered successfully',
        ]);
    }

    public function login(Request $request) 
    {

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User signed in successfully',
            'user' => $user,
            'token' => "Bearer $token"
        ]);
    }

    public function authenticateUser(Request $request)
    {
        $email = $request->email;
        $code = $request->code;
        $expiryMinutes = Constants::EMAIL_VERIFICATION_CODE_EXPIRY_MINUTES;

        $user = User::where('email', $email)->first();

        $verificationCode = VerificationCode::where('email', $email)
            ->where('code', (int) $code)
            ->where('is_used', false)
            ->first();

        if (!$verificationCode) {
            return response()->json(['message' => 'Invalid or expired verification code.'], 401);
        }

        if ($verificationCode->created_at->addMinutes($expiryMinutes)->lt(now())) {
            $verificationCode->delete();
            return response()->json(['message' => 'Invalid or expired verification code.'], 401);
        }

        $verificationCode->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'You have successfully logged in.',
            'user' => $user->fresh(),
            'token' => "Bearer $token"
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function sendEmailCode(Request $request)
    {
        $this->sendOtpToEmail($request->email);

        return response()->json([
            'message' => 'Verification code sent successfully.',
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
            return response()->json(['message' => 'Invalid or expired verification code.'], 401);
        }

        if ($verification->created_at->addMinutes($expiryMinutes)->lt(now())) {
            $verification->delete();
            return response()->json(['message' => 'Invalid or expired verification code.'], 401);
        }

        $verification->is_used = true;
        $verification->save();

        return response()->json([
            'message' => 'You email has been verified successfully',
        ]);
    }

    public function resetUserPassword(Request $request)
    {
        $email = $request->email;
        $code = $request->code;
        $password = $request->password;
        $expiryMinutes = Constants::EMAIL_VERIFICATION_CODE_EXPIRY_MINUTES;

        $user = User::where('email', $email)->first();

        $verificationCode = VerificationCode::where('email', $email)
        ->where('code', (int) $code)
        ->first();

        if (!$verificationCode) {
            return response()->json(['message' => 'Invalid or expired verification code.'], 401);
        }

        if ($verificationCode->created_at->addMinutes($expiryMinutes)->lt(now())) {
            $verificationCode->delete();
            return response()->json(['message' => 'Invalid or expired verification code.'], 401);
        }

        $verificationCode->delete();
        $user->password = Hash::make($request->password);
        $user->tokens()->delete();
        $user->save();

        return response()->json([
            'message' => 'You Password has been reset succesfully.',
        ]);
    }
}
