<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use App\Models\MenuGroup;
use App\Models\MenuDetail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Ambil menuGroups dan urutkan berdasarkan order (ascending)
        $menuGroups = MenuGroup::with(['menuDetails' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->orderBy('order', 'asc')->get();

        // Bagikan data ke semua view
        View::share('menuGroups', $menuGroups);

        Validator::replacer('required', function ($message, $attribute, $rule, $parameters) {
            return "Kolom " . str_replace('_', ' ', $attribute) . " harus diisi!";
        });

        // Jika ingin mengubah nama field di semua validasi
        \Illuminate\Support\Facades\Validator::extendImplicit('custom', function () {
            return false;
        });

        \Illuminate\Support\Facades\Validator::replacer('custom', function ($message, $attribute) {
            return "Kolom " . ucfirst(str_replace('_', ' ', $attribute)) . " wajib diisi!";
        });
    }
}
