<?php

namespace App\Providers;

use App\Support\Icons;
use App\Support\Tpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Mail\HttpApiTransport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Login sessions are stored in the Railway PostgreSQL "sessions" table, so people stay
        // logged in even when Railway restarts or redeploys the app (plain files would be wiped).
        config([
            'database.default' => 'pgsql',
            'session.driver' => env('SESSION_DRIVER', 'database'),
            'cache.default' => 'file',
            'queue.default' => 'sync',
        ]);
    }

    public function boot(): void
    {
        // Helpers available in every Blade view.
        View::share('tpl', new Tpl());
        View::share('icons', new Icons());

        // Railway terminates HTTPS in front of the app, so trust its forwarded headers.
        Request::setTrustedProxies(
            ['*'],
            Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
        );

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            config(['session.secure' => true]); // the login cookie is only sent over HTTPS
        }

        // Email through an HTTPS API (used for the "Forgot password" email).
        Mail::extend('http_api', fn (array $config) => new HttpApiTransport(
            (string) ($config['provider'] ?? 'resend'),
            $config['key'] ?? null
        ));

        $this->ensureDatabaseIsReady();
    }

    /**
     * Runs the database migrations when the server starts (portfolios table, user accounts, sessions).
     * The SQL is "CREATE TABLE IF NOT EXISTS", so existing data is never touched.
     * A small marker file keeps this from running on every request.
     */
    private function ensureDatabaseIsReady(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $ready = storage_path('framework/portfolios-ready-v2');
        $failed = storage_path('framework/portfolios-db-failed');

        if (File::exists($ready)) {
            return;
        }
        if (File::exists($failed) && (time() - File::lastModified($failed)) < 30) {
            return;
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
            File::put($ready, date('c'));
            File::delete($failed);
        } catch (\Throwable $e) {
            File::put($failed, date('c'));
            report($e);
        }
    }
}
