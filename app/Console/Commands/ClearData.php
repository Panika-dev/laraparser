<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearData extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'laraparser:clear';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		if ($this->confirm("Clear tables?", false)) {

			$tables = [
				'pages',
				'items',
			];

			DB::statement('SET foreign_key_checks = 0');

			foreach ($tables as $table) {
				DB::table($table)->truncate();
			}

			DB::statement('SET foreign_key_checks = 1');

			$this->info('Tables cleared');
		} else {
			$this->info('Canceled');
		}
	}
}
