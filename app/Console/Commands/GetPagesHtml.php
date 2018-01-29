<?php

namespace App\Console\Commands;

use App\Contracts\ParserInterface;
use App\Helpers\ParserHelper;
use App\Repositories\PageRepo;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetPagesHtml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraparser:getpageshtml';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get pages';

    /**
     * @var Client
     */
    private $client;

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
     * @param Client $client
     * @param PageRepo $pageRepo
     */
    public function __construct(Client $client, PageRepo $pageRepo, ParserInterface $parser)
    {
        parent::__construct();

        $this->client = $client;
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
        $pages = $this->parser->pages();

        foreach ($pages as $keyPage => $page) {
            $this->pageRepo->create([
                'oid' => $keyPage,
                'url' => $page,
                'status' => 0,
            ]);
        }

        app(ParserHelper::class)->getPages(
            $this->pageRepo,
            $this->pageRepo->findWhere(['status' => 0]),
            $this->pageRepo->findWhere(['status' => 0])->count()
        );

        $this->info('Completed');

    }

    private function getPages($repo, $pages, $countPages)
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

            $repo->updateRich($data, $page->id);

            $this->info($keyPage . '/' . $countPages);
        }

        if ($tryAgain) {
            if ($this->confirm('Try again get html for broken pages?', true)) {
                $this->getPages(
                    $repo,
                    $repo->findWhere(['status' => 3]),
                    $repo->findWhere(['status' => 3])->count()
                );
            }
        }
    }

}
