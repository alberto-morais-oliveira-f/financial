<?php

namespace Am2tec\Financial\Infrastructure\Providers;

use Am2tec\Financial\Application\Services\CategoryService;
use Am2tec\Financial\Application\Services\DreService;
use Am2tec\Financial\Application\Services\WalletService;
use Am2tec\Financial\Application\Services\WebhookService;
use Am2tec\Financial\Domain\Contracts\CategoryRepositoryInterface;
use Am2tec\Financial\Domain\Contracts\CurrencyResolver;
use Am2tec\Financial\Domain\Contracts\DreRepositoryInterface;
use Am2tec\Financial\Domain\Contracts\PaymentRepositoryInterface;
use Am2tec\Financial\Domain\Contracts\RecurringScheduleRepositoryInterface;
use Am2tec\Financial\Domain\Contracts\RefundRepositoryInterface;
use Am2tec\Financial\Domain\Contracts\TitleRepositoryInterface;
use Am2tec\Financial\Domain\Contracts\TransactionRepositoryInterface;
use Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Transaction;
use Am2tec\Financial\Domain\Entities\Wallet;
use Am2tec\Financial\Infrastructure\Adapters\ConfigCurrencyResolver;
use Am2tec\Financial\Infrastructure\Persistence\Repositories\EloquentCategoryRepository;
use Am2tec\Financial\Infrastructure\Persistence\Repositories\EloquentDreRepository;
use Am2tec\Financial\Infrastructure\Persistence\Repositories\EloquentPaymentRepository;
use Am2tec\Financial\Infrastructure\Persistence\Repositories\EloquentRecurringScheduleRepository;
use Am2tec\Financial\Infrastructure\Persistence\Repositories\EloquentRefundRepository;
use Am2tec\Financial\Infrastructure\Persistence\Repositories\EloquentTitleRepository;
use Am2tec\Financial\Infrastructure\Persistence\Repositories\EloquentTransactionRepository;
use Am2tec\Financial\Infrastructure\Persistence\Repositories\EloquentWalletRepository;
use Am2tec\Financial\Infrastructure\Policies\TransactionPolicy;
use Am2tec\Financial\Infrastructure\Policies\WalletPolicy;
use Am2tec\Financial\Infrastructure\Console\Commands\ProcessRecurringSchedules;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FinancialServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Wallet::class => WalletPolicy::class,
        Transaction::class => TransactionPolicy::class,
    ];

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ProcessRecurringSchedules::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../../Config/financial.php' => config_path('financial.php'),
        ], 'financial-config');

        $this->loadViewsFrom(__DIR__ . '/../../../resources/views', 'financial');

        $this->publishes([
            __DIR__ . '/../../../resources/views' => resource_path('views/vendor/financial'),
        ], 'financial-views');

        $this->publishes([
            __DIR__ . '/../../../resources/assets' => public_path('vendor/financial'),
        ], 'financial-assets');

        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        $this->registerRoutes();
        $this->registerPolicies();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../Config/financial.php', 'financial'
        );

        $this->registerFactories();
        $this->registerRepositories();
        $this->registerServices();
    }

    protected function registerFactories(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Am2tec\\Financial\\Infrastructure\\Persistence\\Factories\\' . class_basename($modelName) . 'Factory';
        });
    }

    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../../Application/Api/routes.php');
        });
    }

    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('financial.api.prefix', 'api/financial'),
            'middleware' => config('financial.api.middleware', 'api'),
        ];
    }

    public function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    protected function registerRepositories(): void
    {
        $this->app->bind(\Am2tec\Financial\Domain\Contracts\PaymentGatewayAdapter::class, \Am2tec\Financial\Infrastructure\Adapters\PagarmeAdapter::class);
        $this->app->bind(CurrencyResolver::class, ConfigCurrencyResolver::class);
        $this->app->bind(WalletRepositoryInterface::class, EloquentWalletRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, EloquentTransactionRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, EloquentPaymentRepository::class);
        $this->app->bind(TitleRepositoryInterface::class, EloquentTitleRepository::class);
        $this->app->bind(RecurringScheduleRepositoryInterface::class, EloquentRecurringScheduleRepository::class);
        $this->app->bind(RefundRepositoryInterface::class, EloquentRefundRepository::class);
        $this->app->bind(DreRepositoryInterface::class, EloquentDreRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
    }

    protected function registerServices(): void
    {
        $this->app->singleton(DreService::class);
        $this->app->singleton(WalletService::class);
        $this->app->singleton(WebhookService::class);
        $this->app->singleton(CategoryService::class);
    }
}
