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
	public function getConnectionSuggestions($request)
	: mixed
	{
		$sentRequests = auth()->user()->requestUsers->pluck('requested_user_id');
		$receivedInvites = auth()->user()->receivedRequests->pluck('user_id');
		$connections = auth()->user()->connectedUsers->pluck('connected_user_id');
		$exceptUsers = $sentRequests
			->merge($receivedInvites)
			->merge($connections)
			->unique();

		return $this->model()
			->where('id', '<>', auth()->user()->id)
			->whereNotIn('id', $exceptUsers)
			->paginate($request['takeAmount']);
	}
}