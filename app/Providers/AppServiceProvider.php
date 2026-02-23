<?php

namespace App\Providers;

use App\Api\FirecrawlApi;
use App\Api\Impl\FirecrawlApiImpl;
use App\Api\Impl\SearxngApiImpl;
use App\Api\SearxngApi;
use App\Livewire\Synth\BigDecimalSynth;
use App\Repositories\EventRepository;
use App\Repositories\EventSessionRepository;
use App\Repositories\EventSourceRepository;
use App\Repositories\Impl\EventRepositoryImpl;
use App\Repositories\Impl\EventSessionRepositoryImpl;
use App\Repositories\Impl\EventSourceRepositoryImpl;
use App\Services\EventService;
use App\Services\FirecrawlService;
use App\Services\Impl\EventServiceImpl;
use App\Services\Impl\FirecrawlServiceImpl;
use App\Services\Impl\LlmServiceImpl;
use App\Services\Impl\SearchServiceImpl;
use App\Services\LlmService;
use App\Services\SearchService;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Livewire::propertySynthesizer(BigDecimalSynth::class);

        $this->app->bind(SearxngApi::class, SearxngApiImpl::class);
        $this->app->bind(FirecrawlApi::class, FirecrawlApiImpl::class);
        $this->app->bind(EventRepository::class, EventRepositoryImpl::class);
        $this->app->bind(EventSessionRepository::class, EventSessionRepositoryImpl::class);
        $this->app->bind(EventSourceRepository::class, EventSourceRepositoryImpl::class);
        $this->app->bind(FirecrawlService::class, FirecrawlServiceImpl::class);
        $this->app->bind(EventService::class, EventServiceImpl::class);
        $this->app->bind(LlmService::class, LlmServiceImpl::class);
        $this->app->bind(SearchService::class, SearchServiceImpl::class);
    }

    public function boot(): void
    {
    }
}
