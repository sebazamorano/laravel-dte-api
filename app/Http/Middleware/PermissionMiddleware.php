<?php

namespace App\Http\Middleware;

use Route;
use Closure;
use Illuminate\Http\Request;
use App\Models\RoutePermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $notTenantNeeded = [
            'api.empresas.index',
            'api.empresas.store',
            'api.permissions.index',
            'api.regiones.index',
            'api.comunas.index',
            'api.provincias.index',
            'api.actividadesEconomicas.index',
        ];

        if (in_array($request->route()->getName(), $notTenantNeeded)) {
            return $next($request);
        }

        $tenant = $request->input('empresa_id') ? $request->input('empresa_id') : $request->route('empresa_id');

        if (! $tenant) {
            throw new HttpResponseException(response()->json([
                'message' => 'empresa_id should be present',
                'errors' => ['empresa_id'=>['empresa_id not present.']],
                'status_code' => 400,
            ], JsonResponse::HTTP_BAD_REQUEST));
        }

        $route = Route::currentRouteName();

        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (Auth::user()->isSuperAdmin()) {
            return $next($request);
        }

        $permission = RoutePermission::getPermission($route);

        if (! $permission) {
            throw UnauthorizedException::forPermissions(['permiso no configurado']);
        }

        $pass = is_null($tenant) ? Auth::user()->can($permission) : Auth::user()->hasPermissionToTenant($permission, $tenant);
        if ($pass) {
            return $next($request);
        }

        $permission = [$permission];

        throw UnauthorizedException::forPermissions($permission);
    }
}
