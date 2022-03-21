<?php

namespace Egent\Setting;


use App\Models\User;
use Egent\Notification\Components\Create;
use Egent\Notification\Components\FolderCreate;
use Egent\Notification\Components\Message;
use Egent\Notification\Components\Navigation;
use Egent\Notification\Components\Notification\Loop;
use Egent\Notification\Components\Reply;
use Egent\Notification\Console\Commands\InstallCommand;
use Egent\Notification\Listeners\SendNotification;
use Egent\Notification\Observers\MessageObserver;
use Illuminate\Contracts\Events\Dispatcher;
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
    ];


    public function boot()
    {
	    $this->bootRoutes();
	    $this->loadViewsFrom(__DIR__ . '/Views', 'setting');

	    return;
        /*
        Event::listen('*', function ($eventName, array $data) {
            //
            echo $eventName.'<---'.PHP_EOL;
        });
        */
        // ... other things
        $this->bootComponents();
        $this->commands([
            InstallCommand::class,
        ]);
        $this->bootComponents();
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
		\Egent\Notification\Models\Message::observe(MessageObserver::class);
	}

    /**
     * Boot all components.
     */
    private function bootComponents() : void {
        $this->loadViewComponentsAs('user-notification', [
            Create::class,
            Components\Loop::class,
            Navigation::class,
            Message::class,
            Reply::class,
	        FolderCreate::class
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/config.php', 'setting');
    }
}