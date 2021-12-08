<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/accounts/my';

    public function __construct()
    {
        $this->middleware('guest:client')->except('logout');
    }

    public function index()
    {
        return view('auth.client-login');
    }

    public function firstAccess()
    {
        return view('auth.firstAccess');
    }

    public function createFirstAccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect('/firstAccess')
                ->withErrors($validator)
                ->withInput();
        }

        $email = $request->email;
        $password = $request->password;

        $client = \App\Models\Client::where('email', $email)->first();

        if(!$client) {
            flash('E-mail não encontrado')->error();
            return redirect()->route('client.login');
        }

        $client->forceFill([
            'password' => Hash::make($password)
        ]);

        $client->save();

        flash('Senha cadastrada com sucesso')->success();
        return redirect()->route('client.login');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function guard(){
        return Auth::guard('client');
    }
}
