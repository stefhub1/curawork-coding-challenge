<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReceivedUserCollection;
use App\Http\Resources\RequestUserCollection;
use App\Http\Resources\SuggestionCollection;
use App\Models\CommonConnection;
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
	public function index(Request $request, $tab = 'suggestions')
	{
		$params = $request->all();

		if ($request->ajax()) {
			$response = match ($tab) {
				'suggestions' => new SuggestionCollection($this->userRepo->getConnectionSuggestions($params)),
				'sent' => new RequestUserCollection(auth()->user()->requestUsers()->with('requestedUser')->paginate($params['takeAmount'])),
				'received' => new ReceivedUserCollection(auth()->user()->receivedRequests()->with('user')->paginate($params['takeAmount'])),
				default => null,
			};

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
		$request->user()->connectedUsers()->save(
			new Connection([
				'connected_user_id' => $requestUser->user_id
			])
		);

		// update common connections
		$requestUser->user
			->connectedUsers()
			->pluck('connected_user_id')
			->map(function ($connectUser) use ($requestUser) {
				$checkCommonConnected = auth()->user()->connectedUsers()->where('connected_user_id', $connectUser)->count();
				if ($checkCommonConnected > 0) {
					$checkCommon = CommonConnection::where('user_id', auth()->user()->id)
						->where('common_user_id', $requestUser->user_id)
						->where('common_connected_user_id', $connectUser)
						->count();
					if (!$checkCommon) {
						$newCommonConnect = new CommonConnection();
						$newCommonConnect->user_id = auth()->user()->id;
						$newCommonConnect->common_user_id = $requestUser->user_id;
						$newCommonConnect->common_connected_user_id = $connectUser;
						$newCommonConnect->save();
					}

					$checkCommon1 = CommonConnection::where('common_user_id', auth()->user()->id)
						->where('user_id', $requestUser->user_id)
						->where('common_connected_user_id', $connectUser)
						->count();

					if (!$checkCommon1) {
						$newCommonConnect1 = new CommonConnection();
						$newCommonConnect1->user_id = $requestUser->user_id;
						$newCommonConnect1->common_user_id = auth()->user()->id;
						$newCommonConnect1->common_connected_user_id = $connectUser;
						$newCommonConnect1->save();
					}
				}
			});

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
