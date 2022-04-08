<?php

namespace Egent\Setting;


use App\Models\User;

use Egent\Setting\Components\MessageResponder;
use Egent\Setting\Components\MessageSignature;
use Egent\Setting\Components\TemplateClauses;
use Egent\Setting\Components\TemplateContracts;
use Egent\Setting\Components\TemplateDeadlineExplanations;
use Egent\Setting\Components\TemplateDeadlines;
use Egent\Setting\Components\TemplateMessages;
use Egent\Setting\Components\TemplateTasks;
use Egent\Setting\Console\Commands\InstallCommand;
use Egent\Setting\Events\GoogleConnected;
use Egent\Setting\Policies\SettingPolicy;
use Egent\Task\Facades\CalendarFacade;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TorMorten\Eventy\Facades\Events as Eventy;
use Illuminate\Support\Facades\Event;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * All of the Inbox event / listener mappings.
     *
     * @var array
     */
    protected $events = [
	    \Egent\Setting\Events\GoogleConnected::class => [
		    \Egent\Setting\Listeners\GoogleConnected::class
	    ],
	    \Egent\Setting\Events\TransactionCoordinator\Connected::class => [
		    \Egent\Setting\Listeners\TCConnected::class
	    ],
	    \Egent\Setting\Events\TransactionCoordinator\Disconnected::class => [
		    \Egent\Setting\Listeners\TCDisconnected::class
	    ],
    ];
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides(): array
	{
		return ['setting'];
	}

    public function boot()
    {
	    $this->bootRoutes();
	    $this->loadViewsFrom(__DIR__ . '/Views', 'setting');
	    $this->bootComponents();
		$this->bootPolicy();
	    $this->commands([
		    InstallCommand::class,
	    ]);
        $this->bootEvents();
		$this->bootObservers();

    }

    protected function bootRoutes() : void {
        Route::group([
//            'prefix' => 'user',
//            'as' => 'user.',
            'middleware' => ['web','auth']
        ], function() {
            $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        });



    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }

	/**
	 * Boot all components.
	 */
	private function bootComponents() : void {
		$this->loadViewComponentsAs('setting', [
			MessageResponder::class,
			MessageSignature::class,
			TemplateClauses::class,
			TemplateContracts::class,
			TemplateDeadlineExplanations::class,
			TemplateDeadlines::class,
			TemplateMessages::class,
			TemplateTasks::class
		]);
	}

	/**
     * Register the Inbox events.
     *
     * @return void
     */
    private function bootEvents() : void {
            $events = $this->app->make(Dispatcher::class);

            foreach ($this->events as $event => $listeners) {
                foreach ($listeners as $listener) {
                    $events->listen($event, $listener);
                }
            }
    }

	/**
	 * Bring our observers online.
	 */
	private function bootObservers() : void {
	}


	/**
	 * Bring our policies online.
	 */
	protected function bootPolicy() : void {
		Gate::define('setting-ctm', [SettingPolicy::class, 'ctm']);
		Gate::define('setting-messaging', [SettingPolicy::class, 'messaging']);
		Gate::define('setting-goal-monthly', [SettingPolicy::class, 'goalMonthly']);
        Gate::define('setting-notification', [SettingPolicy::class, 'notification']);
        Gate::define('setting-goal-monthly', [SettingPolicy::class, 'goalMonthly']);
		Gate::define('setting-messaging', [SettingPolicy::class, 'messaging']);
		Gate::define('setting-signature', [SettingPolicy::class, 'signature']);
        Gate::define('setting-template', [SettingPolicy::class, 'template']);
        Gate::define('setting-calendar', [SettingPolicy::class, 'calendar']);
        Gate::define('setting-transaction-coordinator', [SettingPolicy::class, 'transactionCoordinator']);
		
//		Gate::define('transaction-policy', [TransactionPolicy::class, 'show']);
///		Gate::policy('transaction-policy', function($user) { return true; });//[TransactionPolicy::class, 'view']);
		if ($temp = \Config::get('listing.policy')) {
		//	Gate::policy(Listing::class, $temp);
		}
	}

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/config.php', 'setting');

	    $loader = AliasLoader::getInstance();
	    $loader->alias('setting', Setting::class);
	    $this->app->bind('setting',function() {
		    return new Setting($this->app);
	    });
    }
}