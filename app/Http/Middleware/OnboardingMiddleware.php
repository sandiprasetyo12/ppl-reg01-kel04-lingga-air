<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnboardingMiddleware
{
    // Cek apakah user sudah selesai onboarding sebelum akses dashboard
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Kalau belum selesai onboarding, redirect ke langkah yang tepat
        if (!$user->hasCompletedOnboarding()) {
            $step = $user->currentOnboardingStep();
            return redirect()->route("onboarding.{$step}");
        }

        return $next($request);
    }
}