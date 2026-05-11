<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthException;
use App\Repositories\UserRepository;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    public function __construct(
        private readonly JwtService $jwtService,
        private readonly UserRepository $userRepository
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            throw new AuthException('Unauthenticated');
        }

        $payload = $this->jwtService->verifyToken($token);

        $user = $this->userRepository->findById($payload->sub);

        if (!$user) {
            throw new AuthException('User not found');
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
