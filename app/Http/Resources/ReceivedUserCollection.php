<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReceivedUserCollection extends ResourceCollection
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
				'requested_user_id' => $el->user,
				'name'              => $el->user ? $el->user->name : '',
				'email'             => $el->user ? $el->user->email : ''
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
