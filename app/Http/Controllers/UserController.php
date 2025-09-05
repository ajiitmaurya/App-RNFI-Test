<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RNFIApiService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $authService;

    public function __construct(RNFIApiService $authService)
    {
        $this->authService = $authService;
    }
    function login()
    {
        $data = session('data');
        if (!empty($data['token'])) {
            return redirect('/articles');
        }
        return view('login');
    }

    function register()
    {
        $data = session('data');
        if (!empty($data['token'])) {
            return redirect('/articles');
        }
        return view('register');
    }

    function submitRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $response = $this->authService->register($request->all());
        $response = $response['original'] ?? null;
        if (!empty($response['token']) && !empty($response['user'])) {
            session(['data' => $response]);
            return redirect('/articles');
        }

        return redirect('/login')->withErrors([
            'error' => 'Invalid credentials, please try again.'
        ]);
    }

    function submitLogin(Request $request)
    {
        $response = $this->authService->login($request->all());
        $response = $response['original'] ?? null;
        if (!empty($response['token']) && !empty($response['user'])) {
            session(['data' => $response]);
            return redirect('/articles');
        }

        return redirect('/login')->withErrors([
            'error' => 'Invalid credentials, please try again.'
        ]);
    }

    function logout(Request $request)
    {
        $response = $this->authService->logout($request->all());
        session()->flush();
        return redirect('/login');
    }
}
