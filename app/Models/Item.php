<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class Item extends Model {

	protected $table = 'items';

	protected $fillable = [
		'oid',
		'url',
		'html',
		'data',
		'status',
		'page_id',
	];

	protected $casts = [
		'data' => 'array'
	];

	/**
	 * @return BelongsTo|Builder
	 */
	public function page() {
		$this->belongsTo(Page::class);
	}
}