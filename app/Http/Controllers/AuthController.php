<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function loginGet()
    {
        $title = "Login";
        return view('/auth/login', compact("title"));
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $message = "Login success";

            myFlasherBuilder(message: $message, success: true);
            return redirect('/home');
        }

        $message = "Wrong credential";
        myFlasherBuilder(message: $message, failed: true);
        return back();
    }

    public function registrationGet()
    {
        $title = "Registration";
        return view('/auth/register', compact("title"));
    }

    public function registrationPost(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|max:255',
            'username' => 'required|max:15',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|confirmed|min:4',
            'phone' => 'required|numeric',
            'gender' => 'required',
            'address' => 'required',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['image'] = env("IMAGE_PROFILE");
        $validatedData = array_merge($validatedData, [
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
            'role_id' => 2 // value 2 for customer role
        ]);

        try {
            User::create($validatedData);
            $message = "Congratulations, your account has been created!";

            myFlasherBuilder(message: $message, success: true);
            return redirect('/auth/login');
        } catch (\Illuminate\Database\QueryException $exception) {
            return abort(500);
        }
    }

        public function logoutPost()
{
    try {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        $message = "Session ended, you logout <strong>successfully</strong>";
        session()->flash('message', $message);
        session()->flash('alert-class', 'alert-success');
        return redirect( route('landing.index') );
    } catch (\Exception $exception) {
        return abort(500);
    }

    }

   // Forgot Password (Email Validation)
   public function forgotPasswordGet()
   {
       $title = "Forgot Password";
       return view('auth.forgot-password', compact('title'));
   }

   // Forgot Password (Post request to validate email)
   public function forgotPasswordPost(Request $request)
   {
       // Validasi email
       $validatedData = $request->validate([
           'email' => 'required|email:rfc,dns'
       ]);

       // Cek apakah email ada di database
       $user = User::where('email', $validatedData['email'])->first();

       if (!$user) {
        // Email tidak ditemukan
        $message = "Email tidak ditemukan di sistem.";
        session()->flash('message', $message); // Pesan error
        return back(); // Kembali ke form dengan pesan error
    }

    // Jika email ditemukan, beri feedback sukses dan simpan email di sesi
    $message = "Email ditemukan! Anda dapat melanjutkan untuk mereset kata sandi.";
    session()->flash('message', $message); // Pesan sukses

    // Simpan email dalam sesi untuk digunakan di halaman reset password
    session(['email_for_reset' => $user->email]);

    // Arahkan ke halaman reset password
    return redirect()->route('auth.reset-password');
   }

   // Reset Password Get (Menampilkan form untuk reset password)
   public function resetPasswordGet()
   {
       $email = session('email_for_reset');
       if (!$email) {
           return redirect('/auth/forgot-password');
       }

       $title = "Reset Password";
       return view('auth.reset-password', compact('title', 'email'));
   }

   // Reset Password Post (Proses penggantian kata sandi)
   public function resetPasswordPost(Request $request)
   {
       $validatedData = $request->validate([
           'email' => 'required|email:rfc,dns',
           'password' => 'required|confirmed|min:4'
       ]);

       // Verifikasi email yang dimasukkan dengan email yang ada di session
       if ($validatedData['email'] !== session('email_for_reset')) {
           return abort(403, "Unauthorized action.");
       }

       // Update password pengguna
       $user = User::where('email', $validatedData['email'])->first();
       if ($user) {
           $user->password = Hash::make($validatedData['password']);
           $user->save();

           // Hapus session email
           session()->forget('email_for_reset');

           $message = "Password berhasil diperbarui.";
           session()->flash('message', $message); // Pesan sukses

           return redirect('/auth/login');
       }

       return abort(500, "Terjadi kesalahan saat memperbarui password.");
   }
}