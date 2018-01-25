<?php
namespace App\Repositories;

use App\Models\Item;

class ItemRepo extends Repository {

	public function model()
	{
		return Item::class;
	}
}