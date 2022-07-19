<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReceivedUserCollection;
use App\Http\Resources\RequestUserCollection;
use App\Http\Resources\SuggestionCollection;
use App\Models\Connection;
use App\Models\RequestUser;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestUserController extends Controller
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
	public function index(Request $request, $tab = 'btnradio1')
	{
		$params = $request->all();

		if ($request->ajax()) {
			$response = null;

			switch ($tab) {
				case 'btnradio1':
					$response = new SuggestionCollection($this->userRepo->getConnectionSuggestions($params));
					break;

				case 'btnradio2':
					$response = new RequestUserCollection(auth()->user()->requestUsers()->with('requestedUser')->paginate($params['takeAmount']));
					break;

				case 'btnradio3':
					$response = new ReceivedUserCollection(auth()->user()->receivedRequests()->with('user')->paginate($params['takeAmount']));
					break;
			}

			return response()->json([
				'status'  => 'success',
				'user'    => auth()->user()->id,
				'content' => $response
			]);
		}

		$suggestionsCount = $this->userRepo->getConnectionSuggestions($params, true);

		return view('home', compact('tab', 'suggestionsCount'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request)
	: JsonResponse
	{
		$suggestionId = $request->input('suggestionId');

		auth()->user()->requestUsers()->save(
			new RequestUser([
				'requested_user_id' => $suggestionId
			])
		);

		return response()->json([
			'status' => 'success'
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param RequestUser $requestUser
	 * @return JsonResponse
	 */
	public function update(Request $request, RequestUser $requestUser)
	: JsonResponse
	{
		auth()->user()->connectedUsers()->save(
			new Connection([
				'connected_user_id' => $requestUser->requested_user_id
			])
		);

		$requestUser->delete();

		return response()->json([
			'status' => 'success'
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param RequestUser $requestUser
	 * @return JsonResponse
	 */
	public function destroy(RequestUser $requestUser)
	: JsonResponse
	{
		$requestUser->delete();

		return response()->json([
			'status' => 'success'
		]);
	}
}
