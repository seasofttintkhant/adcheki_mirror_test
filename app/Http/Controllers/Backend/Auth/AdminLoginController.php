<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminLoginRequest;

class AdminLoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('login_id', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            if (Auth::guard('admin')->user()->role === 1) {
                return redirect()->intended(route('admin.dashboard'));
            }
            if ($request->ip() !== Auth::guard('admin')->user()->permitted_ip) {
                return redirect()
                    ->back()
                    ->with('invalidLogin', __('auth.invalid_ip'))
                    ->withInput($request->only('login_id'));
            }
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()
            ->back()
            ->with('invalidLogin', __('auth.failed'))
            ->withInput($request->only('login_id'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect(route('admin.login'));
    }
}
