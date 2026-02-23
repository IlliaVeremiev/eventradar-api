<?php

namespace App\Providers;

use App\Livewire\Synth\BigDecimalSynth;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Livewire::propertySynthesizer(BigDecimalSynth::class);
    }

    public function boot(): void
    {
    }
}
