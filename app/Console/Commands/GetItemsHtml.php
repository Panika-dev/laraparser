<?php

namespace App\Console\Commands;

use App\Repositories\ItemRepo;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Yangqi\Htmldom\Htmldom;

class GetItemsHtml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraparser:getitemshtml';

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
     * @var Client
     */
    private $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client, ItemRepo $itemRepo)
    {
        parent::__construct();
        $this->itemRepo = $itemRepo;
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $items = $this->itemRepo->findWhere(['status' => 0]);
        $countItems = count($items);

        $this->getPages($items, $countItems);

        $this->info('Completed');
    }

    private function getPages($pages, $countPages)
    {
        $tryAgain = false;

        foreach ($pages as $keyPage => $page) {
            $resPage = $this->client->request('GET', $page->url);

            $status = $resPage->getStatusCode() == 200 ? 1 : 3;

            if ($status == 1) {
                $tryAgain = true;
                $data = [
                    'status' => $status,
                ];
            } else {
                $data = [
                    'html' => $resPage->getBody(),
                    'status' => $status,
                ];
            }

            $this->itemRepo->updateRich($data, $page->id);

            $this->info($keyPage . '/' . $countPages);
        }

        if ($tryAgain) {
            if ($this->confirm('Try again get html for broken pages?', true)) {
                $this->getPages(
                    $this->itemRepo->findWhere(['status' => 3]),
                    $this->itemRepo->findWhere(['status' => 3])->count()
                );
            }
        }
    }
}
