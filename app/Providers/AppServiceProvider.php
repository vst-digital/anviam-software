<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatGroupMember;
use App\Models\User;
use App\Models\Project;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        view()->composer('*', function ($view) {
            if(Auth::id()){

                $user_projects = Project::where('created_by', Auth::user()->id)->get();
                $chatgroup 	= ChatGroupMember::with('chatgroup')->where('group_member_id', Auth::id())->get();
                $users = User::where('id', '!=', Auth::id())->where('company_id',Auth::user()->company_id)->get();
            }else{
                $chatgroup 	= ChatGroupMember::with('chatgroup')->get();
                $users = User::all();
                $user_projects = Project::all();
            }
            $data = array(  'users' => $users,
                            'chatgroup' => $chatgroup,
                            'user_projects' => $user_projects
                    );

            View()->share('users', $users);
            View()->share('chatgroup', $chatgroup);
            View()->share('user_projects', $user_projects);
            if(Auth::id()){
                $path = base_path('.env');
                // get old value from current env
                $oldValue = env("USERID");

                // was there any change?
                if ($oldValue ==  Auth::user()->id) {
                    return;
                }

                // rewrite file content with changed data
                if (file_exists($path)) {
                    // replace current value with new value
                    file_put_contents(
                        $path, str_replace(
                            'USERID='.$oldValue,
                            'USERID='.Auth::user()->id,
                            file_get_contents($path)
                        )
                    );
                }
            }
        });
    }
}
