<?php

namespace App\Repositories;

use App\Models\RequestUser;

class RequestUserRepository extends Repository
{
	public function model()
	{
		return app(RequestUser::class);
	}
}