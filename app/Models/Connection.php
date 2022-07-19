<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Connection extends Model
{
	use HasFactory;

	protected $fillable = ['user_id', 'connected_user_id'];

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
	 * Get connected user info
	 *
	 * @return BelongsTo
	 */
	public function connectedUser()
	: BelongsTo
	{
		return $this->belongsTo(User::class, 'connected_user_id');
	}
}
