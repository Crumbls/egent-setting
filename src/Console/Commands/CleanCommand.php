<?php

namespace Egent\Setting\Console\Commands;

use Egent\Setting\Models\Setting;
use Illuminate\Console\Command;

/**
 * @internal
 * @deprecated
 */
class CleanCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'settings:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes orphaned settings without users or metadata.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info("Deleted {$this->deleteOrphanedSettings()} orphaned settings.");

        return 0;
    }

    /**
     * Deletes orphaned settings.
     *
     * @return int
     */
    protected function deleteOrphanedSettings(): int
    {
        return Setting::query()
            ->withoutGlobalScopes()
            ->doesntHave('user')
            ->orDoesntHave('metadata')
            ->delete();
    }
}