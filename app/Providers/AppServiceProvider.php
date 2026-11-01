<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\RunningText;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\ManualPaymentService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PaymentGatewayInterface::class,
            function ($app) {
                return $app->make(ManualPaymentService::class);
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            try {
                if (Schema::hasTable('running_texts')) {
                    $runningTexts = RunningText::active()->orderBy('sort_order')->latest('id')->get();
                    $view->with('runningTexts', $runningTexts);
                }
                if (Schema::hasTable('categories')) {
                    $navCategories = Category::active()->orderBy('sort_order')->get();
                    $view->with('navCategories', $navCategories);
                }
            } catch (\Throwable $e) {
                // Silently fallback if database is not migrated yet
            }
        });
    }
}
