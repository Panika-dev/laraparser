<?php
namespace App\Repositories;

use App\Models\Page;

class PageRepo extends Repository {

	public function model()
	{
		return Page::class;
	}
}