<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
	public function model()
	{
		return app(User::class);
	}

	/**
	 * Get Connection suggest users
	 *
	 * @param $request
	 * @return mixed
	 */
	public function getConnectionSuggestions($request, $count = false)
	: mixed
	{
		$sentRequests = auth()->user()->requestUsers->pluck('requested_user_id');
		$receivedInvites = auth()->user()->receivedRequests->pluck('user_id');
		$connections = auth()->user()->connectedUsers->pluck('connected_user_id');
		$exceptUsers = $sentRequests
			->merge($receivedInvites)
			->merge($connections)
			->unique();

		$query = $this->model()
			->where('id', '<>', auth()->user()->id)
			->whereNotIn('id', $exceptUsers);

		return $count ? $query->count() : $query->paginate($request['takeAmount']);
	}
}