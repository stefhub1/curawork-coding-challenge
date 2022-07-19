<?php

namespace App\Http\Controllers;

use App\Http\Resources\RequestCollection;
use App\Models\RequestUser;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
					$response = $this->userRepo->getConnectionSuggestions($params);
					break;
			}

			return response()->json([
				'status'  => 'success',
				'user'    => auth()->user()->id,
				'content' => new RequestCollection($response)
			]);
		}

		$suggestionsCount = $this->userRepo->getConnectionSuggestions($params, true);

		return view('home', compact('tab', 'suggestionsCount'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return void
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request)
	{
		$tab = $request->input('tab');

		if ($tab === 'suggestions') {
			$suggestionId = $request->input('suggestionId');

			auth()->user()->requestUsers()->save(
				new RequestUser([
					'requested_user_id' => $suggestionId
				])
			);
		}

		return response()->json([
			'status' => 'success'
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param RequestUser $requestUser
	 * @return Response
	 */
	public function show(RequestUser $requestUser)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param RequestUser $requestUser
	 * @return Response
	 */
	public function edit(RequestUser $requestUser)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param RequestUser $requestUser
	 * @return Response
	 */
	public function update(Request $request, RequestUser $requestUser)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param RequestUser $requestUser
	 * @return Response
	 */
	public function destroy(RequestUser $requestUser)
	{
		//
	}
}
