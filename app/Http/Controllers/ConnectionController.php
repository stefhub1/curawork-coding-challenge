<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConnectionCollection;
use App\Models\CommonConnection;
use App\Models\Connection;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
	protected UserRepository $userRepo;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(UserRepository $userRepository)
	{
		$this->middleware('auth');

		$this->userRepo = $userRepository;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return JsonResponse|Application|Factory|View
	 */
	public function index(Request $request)
	: View|Factory|JsonResponse|Application
	{
		$params = $request->all();
		if ($request->ajax()) {
			$response = new ConnectionCollection(
				auth()->user()
					->connectedUsers()
					->with('connectedUser')
					->paginate($params['takeAmount'])
			);

			return response()->json([
				'status'  => 'success',
				'user'    => auth()->user()->id,
				'content' => $response
			]);
		}

		$tab = 'btnradio4';

		$suggestionsCount = $this->userRepo->getConnectionSuggestions($params, true);

		return view('home', compact('tab', 'suggestionsCount'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Connection $connection
	 * @return JsonResponse
	 */
	public function destroy(Connection $connection)
	: JsonResponse
	{
		CommonConnection::where('user_id', $connection->user_id)->where('common_user_id', $connection->connected_user_id)->delete();
		CommonConnection::where('common_user_id', $connection->user_id)->where('user_id', $connection->connected_user_id)->delete();

		$connection->delete();

		return response()->json([
			'status' => 'success'
		]);
	}
}
