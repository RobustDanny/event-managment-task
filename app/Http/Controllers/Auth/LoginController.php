<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // if(!$user || !Hash::check($request->password, $user->password)){
        //     throw ValidationException::withMessages([
        //         'email' => 'The credentials you entered are incorrect.',
        //     ]);
        // }

        if(!Auth::attempt($request->only(['email', 'password']))) {
            throw ValidationException::withMessages([
                'email' => 'The credentials you entered are incorrect.',
            ]);
        }
    }
}