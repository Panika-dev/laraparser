<?php

namespace App\Console\Commands;

use App\Repositories\PageRepo;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraparser:getpages';

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
	 * Create a new command instance.
	 *
	 * @param Client $client
	 * @param PageRepo $pageRepo
	 */
    public function __construct(Client $client, PageRepo $pageRepo)
    {
        parent::__construct();

	    $this->client = $client;
	    $this->pageRepo = $pageRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	foreach ($this->pages() as $keyPage => $url) {
		    $resPage = $this->client->request('GET', $url);

		    $this->pageRepo->create([
		    	'oid' => $keyPage,
		    	'url' => $url,
		    	'html' => $resPage->getBody(),
			    'status' => $resPage->getStatusCode() == 200 ? 1 : $resPage->getStatusCode(),
		    ]);
	    }

	    $this->info('Completed');

    }

	protected function pages(): array {
    	$pages = [];

    	return $pages;
	}
}
