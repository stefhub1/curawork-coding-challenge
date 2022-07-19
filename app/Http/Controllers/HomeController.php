<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuggestCollection;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
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
	 * Show the application dashboard.
	 *
	 * @return Renderable
	 */
	public function index()
	: Renderable
	{
		$request = [
			'takeAmount' => 10,
			'page'    => 1
		];

		$suggestions = new SuggestCollection($this->userRepo->getConnectionSuggestions($request));

		return view('home', compact('suggestions'));
	}
}
