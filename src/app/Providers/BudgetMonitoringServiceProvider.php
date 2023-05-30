<?php

/**
 * Created by PhpStorm.
 * User: Salman
 * Date: 23/11/2021
 * Time: 10:42 AM
 */
namespace App\Providers;

use App\Contracts\BudgetMonitoring\BudgetMonitoringLookupContract;
use App\Contracts\BudgetMonitoring\BudgetMonitoringContract;
use App\Contracts\Common\CommonContract;
use App\Contracts\EmailTransportContract;
use App\Contracts\LookupContract;
use App\Contracts\MessageContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Managers\BudgetMonitoring\BudgetMonitoringManager;
use App\Managers\BudgetMonitoring\BudgetMonitoringLookupManager;
use App\Managers\Common\CommonManager;
use App\Managers\EmailTransportManager;
use App\Managers\LookupManager;
use App\Managers\MessageManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use Illuminate\Support\ServiceProvider;

class BudgetMonitoringServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LookupContract::class, LookupManager::class);
        $this->app->bind(EmployeeContract::class, EmployeeManager::class);
        $this->app->bind(EmailTransportContract::class, EmailTransportManager::class);
        $this->app->bind(MessageContract::class, MessageManager::class);
        $this->app->bind(CommonContract::class, CommonManager::class);
        $this->app->bind(BudgetMonitoringLookupContract::class, BudgetMonitoringLookupManager::class);
        $this->app->bind(BudgetMonitoringContract::class, BudgetMonitoringManager::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
