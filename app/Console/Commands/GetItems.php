<?php

namespace App\Console\Commands;

use App\Contracts\ParserInterface;
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
     * @var ParserInterface
     */
    private $parser;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ItemRepo $itemRepo, PageRepo $pageRepo, ParserInterface $parser)
    {
        parent::__construct();
        $this->itemRepo = $itemRepo;
        $this->pageRepo = $pageRepo;
        $this->parser = $parser;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->pageRepo->findWhere(['status' => 1]) as $keyPage => $page) {
            $pageHtmlDom = new Htmldom($page->html);

            //everyone must have url or oid
            $items = $this->parser->findItemsOnPage($pageHtmlDom);

            foreach ($items as $item) {
                $this->itemRepo->create($item + ['status' => 0, 'page_id' => $page->id]);
            }

            $this->pageRepo->updateRich(['status' => 2], $page->id);
        }

        $this->info('Completed');
    }
}
