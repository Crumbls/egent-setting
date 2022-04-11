<?php

namespace Egent\Setting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 *
 * @property-read string $name
 * @property-read string $type
 * @property-read mixed $default
 * @property-read string $bag
 * @property-read string $group
 * @property-read bool $is_enabled
 *
 * @property-read \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Support\Carbon $created_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Egent\Setting\Models\Setting[] $settings
 *
 * @internal
 */
class ListingTaskLibrary extends Model
{
	protected $fillable = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_listing_task_libraries';

	/**
	 * User
	 * @return BelongsTo
	 */
	public function tasks() : HasMany {
		return $this->hasMany(ListingTask::class);
	}
}
