<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConnectionCollection extends ResourceCollection
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
				'connected_user_id' => $el->connected_user_id,
				'name'              => $el->connectedUser ? $el->connectedUser->name : '',
				'email'             => $el->connectedUser ? $el->connectedUser->email : '',
				'count'             => $el->connectedUser ? $el->connectedUser->sharedUsers()
					->where('user_id', $el->user_id)
					->count() : 0,
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
