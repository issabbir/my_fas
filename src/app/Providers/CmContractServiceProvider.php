<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Providers;


use App\Contracts\Ap\ApContract;
use App\Contracts\Ap\ApLookupContract;
use App\Contracts\Cm\CmContract;
use App\Contracts\Cm\CmLookupContract;
use App\Contracts\Common\CommonContract;
use App\Contracts\EmailTransportContract;
use App\Contracts\LookupContract;
use App\Contracts\MessageContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ap\ApManager;
use App\Managers\Cm\CmLookupManager;
use App\Managers\Cm\CmManager;
use App\Managers\Common\CommonManager;
use App\Managers\EmailTransportManager;
use App\Managers\LookupManager;
use App\Managers\MessageManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use Illuminate\Support\ServiceProvider;

class CmContractServiceProvider extends ServiceProvider
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
        $this->app->bind(CmContract::class, CmManager::class);
        $this->app->bind(CmLookupContract::class, CmLookupManager::class);
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
