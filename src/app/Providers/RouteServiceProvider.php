<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapGeneralLedgerRoutes();

        $this->mapCashManagementRoutes();

        $this->mapAccountPayableRoutes();

        $this->mapAccountReceivableRoutes();

        $this->mapBudgetManagementRoutes();

        $this->mapBudgetMonitoringRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapGeneralLedgerRoutes()
    {
        Route::prefix('general-ledger')->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/generalledger.php'));
    }

    protected function mapCashManagementRoutes()
    {
        Route::prefix('cash-management')->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/cashmanagement.php'));
    }

    protected function mapAccountPayableRoutes()
    {
        Route::prefix('account-payable')->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/accountpayable.php'));
    }

    protected function mapAccountReceivableRoutes()
    {
        Route::prefix('account-receivable')->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/accountreceivable.php'));
    }

    protected function mapBudgetManagementRoutes()
    {
        Route::prefix('budget-management')->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/budgetmanagement.php'));
    }

    protected function mapBudgetMonitoringRoutes()
    {
        Route::prefix('budget-monitoring')->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/budgetmonitoring.php'));
    }


}
