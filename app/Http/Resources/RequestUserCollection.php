<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RequestUserCollection extends ResourceCollection
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
				'id'                => $el->id,
				'requested_user_id' => $el->requested_user_id,
				'name'              => $el->requestedUser ? $el->requestedUser->name : '',
				'email'             => $el->requestedUser ? $el->requestedUser->email : ''
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
