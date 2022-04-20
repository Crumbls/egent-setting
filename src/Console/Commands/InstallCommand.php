<?php

namespace Egent\Setting\Console\Commands;

use App\Models\User;
use Egent\Setting\Models\Metadata;
use Egent\Setting\Traits\HasConfig;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Settings Package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$this->handleMigrations();
	    $this->handleSeeding();
		$this->handleUserTrait();
		// There is nothing to do here.
//	    $this->info(__METHOD__);
        return Command::SUCCESS;
    }

	/**
	 * This is an ugly way to run migrations in our package via the install command.
	 * Detect what is here.
	 * Detect what has ran.
	 * Run what hasn't been ran.
	 */
	protected function handleMigrations() : void {
		$path = dirname(dirname(__DIR__)).'/Database/Migrations/';
		$files = glob($path.'*.php');
		$basenames = array_map(function($e) { return basename($e); }, $files);
		$basenames = array_combine(array_map(function($e) {
			return preg_replace('#^.*?([a-z].*?)\.php$#','$1', $e);
		}, $basenames), $basenames);
		$existing = \DB::table('migrations')->where(function($sub) use ($basenames) {
			foreach($basenames as $k => $v) {
				$sub->orWhere('migration','like','%'.$k);
			}
		})->select('migration')->get()->pluck('migration');
		foreach($existing as $k) {
			$k = preg_replace('#^.*?([a-z])#','$1', $k);
			unset($basenames[$k]);
		}
		$x = strlen(base_path(''));
		foreach($basenames as $base => $basename) {
			$file = substr($path.$basename, $x);
			$this->info('Migrating: '.$basename);
			\Artisan::call('migrate', ['--path' => $file]);
		}
	}

	protected function handleUserTrait() : void {
		$model = \Config::get('auth.providers.users.model');
		if (!$model) {
			$this->error('Unable to determine user model.');
		}
		$traits = class_uses_recursive($model);
		$installed = in_array(HasConfig::class, $traits);
		if (!$installed) {
			$this->info('Make sure to add the '.HasConfig::class.' trait to your User model.');
		}
	}

	protected function handleSeeding() : void {
		$push = array(
			array('name' => 'goal.listing-active','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'goal.client-new','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'goal.under-contract','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'goal.listing-closed','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'notification.contract-created','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'notification.contract-signed','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'notification.contract-sent','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'notification.comment-created','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'notification.contract-signed-fully','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'notification.property-status-changed','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'notification.deadline','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
			array('name' => 'notification.upcoming-events-tasks','type' => 'numeric','default' => '0','is_enabled' => '1','group' => 'default','bag' => 'users'),
		);
		$push = array_chunk($push, 10);
		$model = with(new Metadata);
		$connection = $model->getConnection();
		$table = $model->getTable();
		foreach($push as $chunk) {
			$chunk = array_column($chunk, null, 'name');
			$existing = $connection->table($table)->whereIn('name', array_keys($chunk))->select('name')->get()->pluck('name');
			// Ugly.
			foreach($existing as $k) {
				unset($chunk[$k]);
			}
			foreach($chunk as $v) {
				$entity = new Metadata();
				foreach($v as $k => $val) {
					$entity->$k = $val;
				}
				$entity->save();
			}
		}
	}
}
