<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showFormLogin(){
        return view('auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = DB::table('taikhoan')->where('email', $request->email)->first();
        if ($user) {
            session()->forget('user');
            if (Hash::check($request->password, $user->password)) {
                session()->put('user', $user);
                if ($user->is_admin) {
                    return redirect()->route('admin.dashboard');
                }else{
                    return redirect()->route('home');
                }
            }else{
                return back()->withErrors(['Your password is incorrect']);
            }
        }

        return redirect()->route('login')->with('error', 'Oppes! You have entered invalid credentials');

    }

    public function showFormRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request){

        $request->validate([
            'full_name'=>'required',
            'email'=>'required|email|unique:taikhoan,email',
            'password'=>'required|min:8',
        ]);

        DB::table('taikhoan')->insert([
            'ten' => request('full_name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        return redirect()->route('login');
    }

    public function logout(){
        session()->forget('user');
        return redirect()->route('login');
    }
}
