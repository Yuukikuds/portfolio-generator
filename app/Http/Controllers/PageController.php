<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /** Light / dark mode: the choice is saved in a cookie (no JavaScript needed). */
    public function theme(Request $request): RedirectResponse
    {
        $theme = $request->input('theme') === 'dark' ? 'dark' : 'light';

        return back()->withCookie(cookie()->forever('theme', $theme));
    }

    /** Quick check that the site can reach the Railway PostgreSQL database. */
    public function health(): JsonResponse
    {
        try {
            DB::select('select 1');

            return response()->json(['status' => 'ok', 'database' => 'connected']);
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['status' => 'error', 'database' => 'unavailable'], 500);
        }
    }
}
