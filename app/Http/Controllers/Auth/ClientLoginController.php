<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function guard(){
        return Auth::guard('client');
    }
}
