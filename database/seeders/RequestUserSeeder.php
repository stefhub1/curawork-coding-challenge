<?php

namespace Database\Seeders;

use App\Models\RequestUser;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class RequestUserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Factory::create();
		User::all()->map(
			fn(User $user) => RequestUser::factory()
				->count(80)
				->state(function (array $attributes) use ($user, $faker) {
					// Get already inserted request IDs
					$addedRequestIDs = $user->receivedRequests->pluck('requested_user_id');

					// Get new request user ids
					$userIds = User::all()
						->where('id', '<>', $user->id); // except same ID

					if ($addedRequestIDs) {
						// Prevent duplicate Request ID
						$userIds->whereNotIn('id', $addedRequestIDs);
					}

					$userIds = $userIds->pluck('id');

					return [
						'user_id'           => $user->id,
						'requested_user_id' => $faker->randomElement($userIds)
					];
				})
				->create()
		);
	}
}
