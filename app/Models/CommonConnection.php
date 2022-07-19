<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommonConnection extends Model
{
	use HasFactory;

	protected $fillable = ['user_id', 'common_user_id', 'common_connected_user_id'];

	/**
	 * Get user info
	 *
	 * @return BelongsTo
	 */
	public function user()
	: BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	/**
	 * Get common owner info
	 *
	 * @return BelongsTo
	 */
	public function commonUser()
	: BelongsTo
	{
		return $this->belongsTo(User::class, 'common_user_id');
	}

	/**
	 * Get common connected user info
	 *
	 * @return BelongsTo
	 */
	public function commonConnectedUser()
	: BelongsTo
	{
		return $this->belongsTo(User::class, 'common_connected_user_id');
	}
}
