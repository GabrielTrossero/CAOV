<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Check the credentials sent via login form, it works with username or email
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'user'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login_type = filter_var($request->input('user'), FILTER_VALIDATE_EMAIL ) 
            ? 'email' 
            : 'username';

        $request->merge([
            $login_type => $request->input('user')
        ]);

        if (Auth::attempt($request->only($login_type, 'password'))) {
            return redirect()->intended($this->redirectTo);
        }

        return redirect()->back()
            ->withInput()
            ->with([
                'user' => 'Credenciales erróneas, por favor intente nuevamente.',
            ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
