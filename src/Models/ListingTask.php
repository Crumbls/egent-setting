<?php

namespace Egent\Setting\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
class ListingTask extends Model
{
	protected $fillable = ['title','listing_task_library_id','ord'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_listing_tasks';

	public static function boot() {
		parent::boot();
		static::addGlobalScope('order', function (Builder $builder) {
			$builder->orderBy('ord', 'asc');
		});
		static::creating(function($entity) {
			if (!$entity->ord || !is_numeric($entity->ord)) {
				$connection = $entity->getConnection();
				$table = $entity->getTable();
				$query = $temp = $connection->table($table)->where('user_id', $entity->user_id)->orderBy('ord','desc')->select('ord');
				if ($entity->listing_task_library_id) {
					$query->where('listing_task_library_id', $entity->listing_task_library_id);
				}
				$temp = $query->first();
				$entity->ord = $temp ? $temp->ord + 1 : 1;
			}
		});
	}

	/**
	 * User
	 * @return BelongsTo
	 */
	public function user() : BelongsTo {
		return $this->belongsTo(User::class);
	}

	/**
	 * User
	 * @return BelongsTo
	 */
	public function library() : BelongsTo {
		return $this->belongsTo(ListingTaskLibrary::class);
	}
}
