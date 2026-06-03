<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Menggunakan Auth::check() dari Illuminate\Support\Facades\Auth
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect('/admin/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        return $next($request);
    }
}