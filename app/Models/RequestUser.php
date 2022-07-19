<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestUser extends Model
{
	use HasFactory;

	protected $fillable = ['user_id', 'requested_user_id'];

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
	 * Get requested user info
	 *
	 * @return BelongsTo
	 */
	public function requestedUser()
	: BelongsTo
	{
		return $this->belongsTo(User::class, 'requested_user_id');
	}
}
