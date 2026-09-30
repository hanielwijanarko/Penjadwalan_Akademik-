<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi akses route berdasarkan kolom `users.role`.
 *
 * Pemakaian: Route::middleware('role:admin') atau 'role:admin,user'.
 */
class EnsureUserHasRole
{
    /**
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->is_active || ! in_array($user->role, $roles, true)) {
            abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
