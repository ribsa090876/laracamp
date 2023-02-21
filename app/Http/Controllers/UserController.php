<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\User\AfterRegister;

class UserController extends Controller
{
    // Menampilkan halaman login
    public function login()
    {
        return view('auth.user.login');
    }

    // google Menggunakan Socialite google
    public function google()
    {
        return Socialite::driver('google')->redirect();
    }

    // handleProviderCallback digunakan untuk menambahkan akun dari google dan login menggunakan google
    public function handleProviderCallback()
    {
    // untuk mengambil data profile dari google
       $callback = Socialite::driver('google')->stateless()->user();
       $data = [
        'name' => $callback->getName(),
        'email' => $callback->getEmail(),
        'avatar' => $callback->getAvatar(),
        'email_verified_at' => date('Y-m-d H:i:s', time()),
       ];
    // untuk menambahkan akun jika akun sudah ada maka akan masuk jika belum maka akan dibuatkan akun baru
    // $user = User::firstOrCreate(['email' => $data['email']],$data);

    // untuk menambahkan akun jika akun sudah ada maka akan masuk jika belum maka akan dibuatkan akun baru
    // namun dengan catatan akun tersebut harus bersifat unik dan mengirimkan email ke pendaftar bahwa akun tersebut sudah terdaftar.
      $user = User::whereEmail($data['email'])->first();
      if(!$user){
        $user = User::create($data);
        Mail::to($user->email)->send(new AfterRegister($user));
      }
      Auth::login($user, true);

      return redirect(route('welcome'));
    }
}
