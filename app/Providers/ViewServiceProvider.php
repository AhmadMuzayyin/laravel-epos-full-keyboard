<?php

namespace App\Providers;

use App\Models\Member;
use App\Models\Suplier;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('pages.item.create', function ($view) {
            $supliers = Suplier::all();
            $view->with('supliers', $supliers);
        });
        view()->composer('pages.pembelian.*', function ($view) {
            $supliers = Suplier::all()->toArray();
            $view->with('supliers', $supliers);
        });
        view()->composer('pages.penjualan.*', function ($view) {
            $members = Member::all()->toArray();
            $view->with(['members' => $members]);
        });
    }
}
