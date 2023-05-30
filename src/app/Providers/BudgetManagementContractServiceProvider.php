<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Providers;


use App\Contracts\BudgetManagement\BudgetMgtContract;
use App\Contracts\BudgetManagement\BudgetMgtLookupContract;
use App\Contracts\Common\CommonContract;
use App\Contracts\EmailTransportContract;
use App\Contracts\LookupContract;
use App\Contracts\MessageContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Managers\BudgetManagement\BudgetMgtLookupManager;
use App\Managers\BudgetManagement\BudgetMgtManager;
use App\Managers\Common\CommonManager;
use App\Managers\EmailTransportManager;
use App\Managers\LookupManager;
use App\Managers\MessageManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use Illuminate\Support\ServiceProvider;

class BudgetManagementContractServiceProvider extends ServiceProvider
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
        $this->app->bind(BudgetMgtContract::class, BudgetMgtManager::class);
        $this->app->bind(BudgetMgtLookupContract::class, BudgetMgtLookupManager::class);
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
