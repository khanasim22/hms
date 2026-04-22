<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Laracasts\Flash\Flash;

class TwofactorAuthenticationController extends AppBaseController
{
    public function index()
    {
        $user = Auth::user();

        if ($user->google2fa_secret) {
            return view('two_auth.disable2fa');
        }

        return view('two_auth.enable2fa');
    }

    public function generateTwoFactorSecret()
    {
        $google2fa = app('pragmarx.google2fa');
        $user = Auth::user();

        $secret = $google2fa->generateSecretKey();

        $qrUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            )
        );

        $qrCodeSvg = $writer->writeString($qrUrl);
        session(['2fa_secret' => $secret]);

        return $this->sendResponse(['qrImage' => $qrCodeSvg,'secret' => $secret], __('messages.two_factor_auth.code_generated_successfully'));
    }

    public function enable2FA(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $google2fa = app('pragmarx.google2fa');
        $secret = session('2fa_secret');

        if (!$secret) {
            return response()->json([
                'success' => false,
                'message' => __('messages.two_factor_auth.session_expired')
            ]);
        }

        $valid = $google2fa->verifyKey($secret, $request->otp);

        if (!$valid) {
            return response()->json([
                'success' => false,
                'message' => __('messages.two_factor_auth.invalid_auth_code')
            ]);
        }

        $recoveryCodes = collect(range(1, 8))->map(function () {
            return Str::random(16);
        })->toArray();

        $user = Auth::user();
        $user->google2fa_secret = $secret;
        $user->enable_two_factor_authentication = true;
        $user->two_factor_recovery_codes = Crypt::encrypt(json_encode($recoveryCodes));
        $user->save();

        session()->forget('2fa_secret');

        return response()->json(['success' => true,'codes' => $recoveryCodes,'message' => __('messages.two_factor_auth.two_factor_enabled_successfully')]);
    }

    public function regenerateRecoveryCodes()
    {
        $user = Auth::user();

        $codes = collect(range(1, 8))->map(function () {
            return Str::random(16);
        })->toArray();

        $user->two_factor_recovery_codes = Crypt::encrypt(json_encode($codes));
        $user->save();

        return response()->json([ 'success' => true, 'codes' => $codes, 'message' => __('messages.two_factor_auth.recovery_codes_regenerated_successfully') ]);
    }


    public function disable(Request $request)
    {
        $user = Auth::user();

        $user->google2fa_secret = null;
        $user->enable_two_factor_authentication = false;
        $user->two_factor_recovery_codes = null;
        $user->save();

        Flash::success(__('messages.two_factor_auth.two_factor_disabled_successfully'));
        return redirect()->route('enable-2fa');
    }

    public function showVerifyForm()
    {
        if (!session('2fa:user:id')) {
            return redirect()->route('login');
        }

        return view('two_auth.verify2fa');
    }
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required',
        ]);

        $user = User::find(session('2fa:user:id'));

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['error' => __('messages.two_factor_auth.user_not_found')]);
        }

        $input = trim($request->verification_code);
        $google2fa = app('pragmarx.google2fa');

        if (ctype_digit($input) && strlen($input) === 6) {
            if ($google2fa->verifyKey($user->google2fa_secret, $input)) {
                Auth::login($user);
                session()->forget('2fa:user:id');
                return $this->redirectUser($user);
            }
        }

        try {
            $decrypted = Crypt::decrypt($user->two_factor_recovery_codes);
            $backupCodes = collect(json_decode($decrypted, true));
        } catch (\Exception $e) {
            $backupCodes = collect([]);
        }

        $matched = $backupCodes->first(function ($code) use ($input) {
            return trim($code) === trim($input);
        });

        if ($matched) {
            $updatedCodes = $backupCodes->reject(function ($code) use ($input) {
                return trim($code) === trim($input);
            })->values();

            $user->two_factor_recovery_codes = Crypt::encrypt(json_encode($updatedCodes));
            $user->save();

            Auth::login($user);
            session()->forget('2fa:user:id');

            return $this->redirectUser($user);
        }

        return back()->withErrors([
            'verification_code' => __('messages.two_factor_auth.invalid_auth_code'),
        ]);
    }

    private function redirectUser($user)
    {
        if ($user->hasRole('Admin')) {
            return redirect()->intended('dashboard');
        } elseif ($user->hasRole(['Receptionist'])) {
            return redirect()->intended('appointments');
        } elseif ($user->hasRole(['Doctor', 'Case Manager', 'Lab Technician', 'Pharmacist'])) {
            return redirect()->intended('employee/doctor');
        } elseif ($user->hasRole(['Patient'])) {
            return redirect()->intended('patient-dashboard');
        } elseif ($user->hasRole(['Nurse'])) {
            return redirect()->intended('bed-types');
        } elseif ($user->hasRole(['Accountant'])) {
            return redirect()->intended('accounts');
        } else {
            return redirect()->intended('employee/notice-board');
        }
    }
}
