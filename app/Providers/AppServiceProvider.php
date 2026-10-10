<?php

namespace App\Providers;

use App\Support\Icons;
use App\Support\Tpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // PortfolioCraft only needs the Railway PostgreSQL "portfolios" table.
        // Sessions and cache use plain files, so no other tables are required.
        config([
            'database.default' => 'pgsql',
            'session.driver' => 'file',
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
        }

        $this->ensureDatabaseIsReady();
    }

    /**
     * Creates the portfolios table when the server starts, if it does not exist yet.
     * The SQL is "CREATE TABLE IF NOT EXISTS", so existing data is never touched.
     * A small marker file keeps this from running on every request.
     */
    private function ensureDatabaseIsReady(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $ready = storage_path('framework/portfolios-ready');
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
