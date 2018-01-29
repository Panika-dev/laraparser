<?php

namespace App\Console\Commands;

use App\Contracts\ParserInterface;
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
        $file = fopen('data.csv', 'w+');
        fwrite($file, implode("\t", $this->parser->fields()) . "\n");

        foreach ($this->itemRepo->all() as $keyItem => $item) {
            fwrite($file, implode("\t", $this->getData($item)) . "\n");
        }

        fclose($file);

        $this->info('Completed');
    }

    public function getData($item)
    {
        $data = [];

        foreach ($this->parser->fields() as $field) {
            if (method_exists($this->parser, $field . 'CsvCallback')) {
                $data[$field] = $this->parser->{$field . 'CsvCallback'}($item->data[$field]);
            } else {
                $data[$field] = $item->data[$field];
            }
        }

        $data = $this->parser->customUpdateDataCsv($data);

        return $data;
    }
}
