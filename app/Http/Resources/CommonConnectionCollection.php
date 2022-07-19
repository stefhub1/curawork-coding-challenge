<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommonConnectionCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param Request $request
	 * @return array
	 */
	public function toArray($request)
	: array
	{
		return [
			'list'       => $this->collection->map(fn($el) => [
				'id'             => $el->id,
				'common_user_id' => $el->common_user_id,
				'name'           => $el->commonConnectedUser ? $el->commonConnectedUser->name : '',
				'email'          => $el->commonConnectedUser ? $el->commonConnectedUser->email : ''
			]),
			'pagination' => [
				'total'       => $this->total(),
				'count'       => $this->count(),
				'takeAmount'  => $this->perPage(),
				'currentPage' => $this->currentPage(),
				'lastPage'    => $this->lastPage(),
			]
		];
	}
}
