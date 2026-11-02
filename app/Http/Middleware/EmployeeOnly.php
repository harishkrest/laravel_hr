<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isEmployee()) {
            abort(403, 'Employee access only.');
        }

        $employee = $user->employee;

        if (!$employee || $employee->employment_status !== 'active') {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your employee account is inactive or not linked.',
                ]);
        }

        return $next($request);
    }
}
