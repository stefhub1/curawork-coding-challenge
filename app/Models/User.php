<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	/**
	 * Sent Request Users
	 *
	 * @return HasMany
	 */
	public function requestUsers()
	: HasMany
	{
		return $this->hasMany(RequestUser::class, 'user_id');
	}

	/**
	 * Received Requests
	 *
	 * @return HasMany
	 */
	public function receivedRequests()
	: HasMany
	{
		return $this->hasMany(RequestUser::class, 'requested_user_id');
	}

	/**
	 * Connected Users
	 *
	 * @return HasMany
	 */
	public function connectedUsers()
	: HasMany
	{
		return $this->hasMany(Connection::class, 'user_id');
	}

	/**
	 * Connected in Common
	 * @return HasMany
	 */
	public function commonConnectedUsers()
	: HasMany
	{
		return $this->hasMany(CommonConnection::class, 'user_id');
	}

	public function sharedUsers()
	: HasMany
	{
		return $this->hasMany(CommonConnection::class, 'common_user_id');
	}
}
