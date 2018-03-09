<?php

namespace App\Console\Commands;

use App\Contracts\ParserInterface;
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
     * @var ParserInterface
     */
    private $parser;

    /**
     * Create a new command instance.
     *
     * @param ItemRepo $itemRepo
     * @param ParserInterface $parser
     */
    public function __construct(ItemRepo $itemRepo, ParserInterface $parser)
    {
        parent::__construct();
        $this->itemRepo = $itemRepo;
        $this->parser = $parser;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $items = $this->itemRepo->findWhere(['status' => 1]);
        $countItems = count($items);

        $this->info($countItems);

        foreach ($items as $keyItem => $item) {
            $this->info($keyItem . '/' . $countItems);

            $itemHtmlDom = new Htmldom($item->html);

            $data = [];

            foreach ($this->parser->fields() as $field) {
                try {
                    if (method_exists($this->parser, $field . 'DataFind')) {
                        $data[$field] = $this->parser->{$field . 'DataFind'}($itemHtmlDom);

                        if (method_exists($this->parser, $field . 'DataChange')) {
                            $data[$field] = $this->parser->{$field . 'DataChange'}($data[$field]);
                        }
                    }
                } catch (\Exception $e) {
                    $data[$field] = null;
                }
            }

            $data = $this->parser->customUpdateData($data);

            $this->itemRepo->updateRich(['data' => $data, 'status' => 2], $item->id);
        }

        $this->info('Completed');
    }
}
