<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Chat;
use App\Repositories\CourseRepository;
use App\Repositories\Interfaces\CoursesInterface;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CoursesInterface::class, CourseRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);
        Carbon::setLocale('id');

        View::composer(['partials.top-navbar'], function ($view) {

            $data = Chat::select('chats.*', 'users.user_id', 'users.user_name')
                ->groupBy('from')
                ->join('users', 'users.user_id', '=', 'chats.from')->where('to', Auth::user()->user_id)->get();

            $chatIsRead = Chat::where('to', Auth::user()->user_id)
                ->where('isRead', 0)
                ->orderBy('created_at', 'DESC')
                ->first();

            $view->with(compact('data', 'chatIsRead'));
        });
    }
}
