<?php

namespace App\Providers;

use App\Models\Astrology\Astro;
use App\Models\Date;
use App\Models\Zodiac\Zodiac;
use App\Services\Astrology\AstrologyService;
use App\Services\Astrology\AstroSeekService;
use App\Services\Astrology\Contracts\AstrologyContract;
use App\Services\Astrology\Repositories\AstroRepository;
use App\Services\Date\DateService;
use App\Services\Date\Repositories\DateRepository;
use App\Services\Wiki\SPARQLQueryDispatcher;
use App\Services\Wiki\WikiService;
use App\Services\Zodiac\Repositories\ZodiacRepository;
use App\Services\Zodiac\ZodiacService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerDate();
        $this->registerZodiac();
        $this->registerAstrology();
        $this->registerWiki();
    }

    private function registerDate(): void
    {
        $this->app->singleton(DateRepository::class, fn($app) => new DateRepository(Date::class));

        $this->app->singleton(DateService::class, fn($app) => new DateService($app->make(DateRepository::class)));
    }

    private function registerZodiac(): void
    {
        $this->app->singleton(ZodiacRepository::class, fn($app) => new ZodiacRepository(Zodiac::class));

        $this->app->singleton(ZodiacService::class, fn($app) => new ZodiacService($app->make(ZodiacRepository::class)));
    }

    private function registerAstrology(): void
    {
        $this->app->singleton(AstroSeekService::class, fn($app) => new AstroSeekService());
        $this->app->singleton(AstroRepository::class, fn($app) => new AstroRepository(Astro::class));
        $this->app->bind(AstrologyContract::class, AstroSeekService::class);

        $this->app->singleton(AstrologyService::class, fn($app) => new AstrologyService(
            $app->make(AstrologyContract::class),
            $app->make(AstroRepository::class),
            $app->make(DateService::class),
            $app->make(ZodiacService::class),
        ));
    }

    private function registerWiki(): void
    {
        $this->app->singleton(SPARQLQueryDispatcher::class, fn($app) => new SPARQLQueryDispatcher());
        $this->app->singleton(WikiService::class, fn($app) => new WikiService($app->make(SPARQLQueryDispatcher::class)));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }


}
