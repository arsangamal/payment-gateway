<?php

namespace App\Http\Controllers\Auth;

use App\APIResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auh\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        // Registration logic goes here
        $user = User::create($request->validated());

        return APIResponse::success(
            ['user' => $user],
            'User registered successfully');
    }
}
