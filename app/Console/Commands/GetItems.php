<?php

namespace App\Console\Commands;

use App\Repositories\{
	ItemRepo, PageRepo
};
use Illuminate\Console\Command;
use Yangqi\Htmldom\Htmldom;

class GetItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraparser:getitems';

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
	 * @var PageRepo
	 */
	private $pageRepo;

	/**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ItemRepo $itemRepo, PageRepo $pageRepo)
    {
        parent::__construct();
	    $this->itemRepo = $itemRepo;
	    $this->pageRepo = $pageRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	DB::beginTransaction();

        foreach ($this->pageRepo->all() as $keyPage => $page) {
        	foreach ($this->getItems($page) as $item) {
        		$this->itemRepo->create($item);
	        }

	        $this->pageRepo->updateRich(['status' => 2], $page->id);
        }

	    DB::commit();

        $this->info('Completed');
    }

	private function getItems($page):array {
    	$items = [];

    	//find items;
		$pageHtmlDom = new Htmldom($page->html);
//		$pageItems = $pageHtmlDom->find();
		$pageItems = [];

		foreach ($pageItems as $pageItem) {
			$items[] = [
//				'oid' => $pageItem->find(),
//				'url' => $pageItem->find(),
//				'html' => $pageItem->find(),
				'status' => 0 // if has html - 1
			];
		}

    	return $items;
	}
}
