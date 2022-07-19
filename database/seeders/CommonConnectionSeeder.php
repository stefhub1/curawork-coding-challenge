<?php

namespace Database\Seeders;

use App\Models\CommonConnection;
use App\Models\Connection;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CommonConnectionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Factory::create();

		// Create connections
		User::all()
			->map(
				fn($user) => Connection::factory()
					->count(50)
					->state(function (array $attributes) use ($user, $faker) {
						// Get random request user
						$randomRequestUser = $user->requestUsers
							->find(
								$faker->randomElement($user->requestUsers->pluck('id'))
							);

						$ownerId = $randomRequestUser->user_id;
						$connectedId = $randomRequestUser->requested_user_id;

						// Delete accepted connections in the requested users table
						$randomRequestUser->delete();

						return [
							'user_id'           => $ownerId,
							'connected_user_id' => $connectedId
						];
					})
					->create()
			);

		// Collect and create common connections
		$user = User::find(1);
		User::where('id', '<>', $user->id)
			->get()
			->map(function ($otherUser) use ($user) {
				// Insert common connections
				$user->connectedUsers
					->pluck('connected_user_id')
					->intersect(
						$otherUser->connectedUsers
							->pluck('connected_user_id')
					)
					->map(function ($commonConnection) use ($user, $otherUser) {
						$commonConnectedCount = CommonConnection::where('user_id', $user->id)
							->where('common_user_id', $otherUser->id)
							->where('common_connected_user_id', $commonConnection)
							->count();

						if (!$commonConnectedCount) {
							$newCommonConnect = new CommonConnection();
							$newCommonConnect->user_id = $user->id;
							$newCommonConnect->common_user_id = $otherUser->id;
							$newCommonConnect->common_connected_user_id = $commonConnection;
							$newCommonConnect->save();
						}
					});
			});
	}
}
