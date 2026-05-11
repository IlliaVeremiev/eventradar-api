<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoogleSignInRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function googleSignIn(GoogleSignInRequest $request)
    {
        $tokens = $this->authService->googleSignIn($request->validated('credential'));

        return response()->json([
            'accessToken' => $tokens->accessToken,
            'refreshToken' => $tokens->refreshToken,
        ]);
    }

    public function refresh(RefreshTokenRequest $request)
    {
        $tokens = $this->authService->refresh($request->validated('refreshToken'));

        return response()->json([
            'accessToken' => $tokens->accessToken,
            'refreshToken' => $tokens->refreshToken,
        ]);
    }

    public function logout(RefreshTokenRequest $request)
    {
        $this->authService->logout($request->validated('refreshToken'));

        return response()->noContent();
    }
}
