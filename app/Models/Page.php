<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model {

	protected $table = 'pages';

	protected $fillable = [
		'oid',
		'url',
		'html',
		'data',
		'status',
	];

	protected $casts = [
		'data' => 'array'
	];
}