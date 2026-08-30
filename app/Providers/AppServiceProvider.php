<?php
// app/Providers/AppServiceProvider.php Schema::defaultStringLength(125);

namespace App\Providers;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
 
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
 
    public function boot(): void
    {
        // Fix MySQL "key too long" error (utf8mb4)
        Schema::defaultStringLength(191);
 
        // Pagination avec Tailwind CSS
        Paginator::useTailwind();
 
        // Carbon en français
        Carbon::setLocale('fr');
    }
}
 
 

