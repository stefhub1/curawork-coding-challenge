<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommonConnectionCollection;
use App\Models\CommonConnection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonConnectionController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return JsonResponse
	 */
	public function index(Request $request)
	: JsonResponse
	{
		$response = CommonConnection::where('user_id', $request->user()->id)
			->where('common_user_id', $request->input('connected_id'))
			->paginate($request->input('takeAmount'));

		return response()->json([
			'status'  => 'success',
			'user'    => $request->user()->id,
			'content' => new CommonConnectionCollection($response)
		]);
	}
}
