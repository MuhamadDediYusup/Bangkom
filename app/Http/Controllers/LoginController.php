<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(Request $request)
    {
        // melakukan validasi
        $rules = [
            'user_id' => 'required|numeric',
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ];

        $message = [
            'user_id.required' => 'User masih kosong !',
            'user_id.numeric' => 'User harus berupa angka 18 digit! ',
            'password.required' => 'Password masih kosong !',
            'g-recaptcha-response.required' => 'Harap verifikasi bahwa Anda bukan robot.',
            'g-recaptcha-response.captcha' => 'Kesalahan captcha! coba lagi nanti atau hubungi admin situs.',
        ];

        $pegawai = DB::table('pegawai')->where('nip', $request->user_id)->first();
        if (empty($pegawai)) {
            return back()->with('loginError', 'Maaf.. Anda sudah tidak terdaftar sebagai pegawai, silahkan hubungi admin untuk mengaktifkan akun.');
        }


        try {
            //jalankan validasi
            $validator = Validator::make($request->all(), $rules, $message);

            //cek validasi
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput($request->all());
            }

            $credentials = $request->only('user_id', 'password');

            if (Auth::attempt($credentials)) {
                // Update last login
                $dbUser = new \App\Models\User();
                $user = Auth::user();
                $id = $user->id;
                $time = date('Y-m-d H:i:s');
                $dbUser = $dbUser->find($id);
                // update login count
                $dbUser->login_count = $dbUser->login_count + 1;
                $dbUser->login_time = $time;
                $dbUser->session = 'Logged';
                $dbUser->timestamps = false;
                $dbUser->save();
                return redirect()->intended('/');
            }

            return back()->with('loginError', 'Uppss.. Login masih gagal, Silahkan periksa kembali User dan Password Saudara !');
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            return back()->with('loginError', 'Session Expired, <br> Silahkan Login kembali !');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $id = $user->id;
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Update last logout
        $dbUser = new \App\Models\User();
        $time = date('Y-m-d H:i:s');
        $dbUser = $dbUser->find($id);
        $dbUser->logout_time = $time;
        $dbUser->session = 'Logout';
        $dbUser->timestamps = false;
        $dbUser->save();

        return redirect('/login');
    }

    // public function distroy($id)
    // {
    //     User::find($id)->delete();
    //     return redirect()->route('user.index')
    //         ->with('success', 'User Berhasil Dihapus');
    // }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }

    public function checkSession()
    {
        $this->middleware('guest', ['except' => ['logout', 'checkSession']]);

        if (Auth::guest() == true) {
            $dbUser = new \App\Models\User();
            $user = Auth::user();
            $id = $user->id;
            $time = date('Y-m-d H:i:s');
            $dbUser = $dbUser->find($id);
            $dbUser->logout_time = $time;
            $dbUser->session = 'Expired';

            Auth::logout();
            session()->flush();
            return redirect('/login');
        }

        return response()->json(['guest' => Auth::guest()]);
    }
}
