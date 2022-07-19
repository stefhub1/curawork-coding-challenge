<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class SuggestionCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param Request $request
	 * @return array|Arrayable|JsonSerializable
	 */
	public function toArray($request)
	: array|JsonSerializable|Arrayable
	{
		return [
			'list'       => $this->collection->map(fn($el) => [
				'id'    => $el->id,
				'name'  => $el->name,
				'email' => $el->email
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
