<?php

namespace Egent\Setting\Models;

use App\Models\User;
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
class Message extends Model
{
	protected $fillable = ['*'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_messages';

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
	public function messageLibrary() : BelongsTo {
		return $this->belongsTo(MessageLibrary::class);
	}
}
