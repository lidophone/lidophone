<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\AdminPolicy;
use Closure;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use w3lifer\phpHelper\PhpHelper;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    public function __construct($app)
    {
        parent::__construct($app);
        $models = $this->getAllModels();
        unset($models['App\Models\Uis\UisCall']);
        $this->policies = array_map(fn () => AdminPolicy::class, $models);
    }

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('isAdmin', $this->isAdmin());
    }

    private function getAllModels(): array
    {
        $pathToModels = app_path() . '/Models';
        $modelPaths = PhpHelper::getFilesInDirectory($pathToModels, true);
        $modelList = [];
        foreach ($modelPaths as $modelPath) {
            $modelPath = str_replace($pathToModels, '', $modelPath);
            $modelPath = substr($modelPath, 0, -4);
            $modelPath = str_replace('/', '\\', $modelPath);
            $modelList[] = 'App\Models' . $modelPath;
        }
        return array_flip($modelList);
    }

    private function isAdmin(): Closure
    {
      return fn (User $user) => $user::isAdmin();
    }
}
