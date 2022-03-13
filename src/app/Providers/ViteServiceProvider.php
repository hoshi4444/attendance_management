<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class ViteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('vite', function () {
            if (App::isLocal()) {
                return new HtmlString(<<<HTML
                <script type="module" src="http://localhost:3000/resources/js/app.ts"></script>
            HTML);
            } else {
                $manifest = json_decode(File::get(public_path('dist/manifest.json')), true);
                $appJs = $this->getAppJs($manifest);
                $appCss = $this->getAppCss($manifest);
                return new HtmlString(<<<HTML
                <script type="module" src="/dist/{$appJs}"></script>
                <link rel="stylesheet" href="/dist/{$appCss}">
            HTML);
            }
        });
    }

    private function getAppJs(array $manifest) {
        return $manifest['resources/js/app.ts']['file'];
    }

    private function getAppCss(array $manifest) {
        return $manifest['resources/js/app.ts']['css'][0];
    }
}
