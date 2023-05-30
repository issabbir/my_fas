<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 02/11/20
 * Time: 11:11 AM
 */

namespace App\Providers;


use App\Contracts\Common\CommonContract;
use App\Contracts\EmailTransportContract;
use App\Contracts\Gl\GlContract;
use App\Contracts\LookupContract;
use App\Contracts\MessageContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Managers\Common\CommonManager;
use App\Managers\EmailTransportManager;
use App\Managers\Gl\GlManager;
use App\Managers\LookupManager;
use App\Managers\MessageManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use Illuminate\Support\ServiceProvider;

class GlContractServiceProvider extends ServiceProvider
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
        $this->app->bind(GlContract::class, GlManager::class);
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
