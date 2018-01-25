<?php

namespace App\Console\Commands;

use App\Repositories\ItemRepo;
use Illuminate\Console\Command;
use Yangqi\Htmldom\Htmldom;

class GetItemsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraparser:getitemsdata';

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
	    foreach ($this->itemRepo->all() as $keyItem => $item) {
	    	$data = [];
	    	$itemHtmlDom = new Htmldom($item->html);

//	    	$data['name'] = $itemHtmlDom->find();

		    $this->itemRepo->updateRich(['data' => $data, 'status' => 2], $item->id);
	    }
    }
}
