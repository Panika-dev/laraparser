<?php

namespace App\Console\Commands;

use App\Repositories\ItemRepo;
use Illuminate\Console\Command;

class GetCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraparser:getcsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

	/**
	 * @var ItemRepo
	 */
	private $itemRepo;

	/**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ItemRepo $itemRepo)
    {
        parent::__construct();
	    $this->itemRepo = $itemRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $file = fopen('data.csv', 'w+');
	    fwrite($file, implode("\t", [
			    'id',
			    'name',
		    ]) . "\n");

	    foreach ($this->itemRepo->all() as $keyItem => $item) {
		    fwrite($file, implode("\t", $item['data']) . "\n");
	    }

	    fclose($file);

	    $this->info('Completed');
    }
}
