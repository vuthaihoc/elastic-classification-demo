<?php

namespace App\Console\Commands;

use App\Models\Text;
use Illuminate\Console\Command;

class TextSearchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'text:search {string : Search string} {--c|classify : Show topic of string}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test search from Text';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $string = $this->argument('string');
        $this->info( "Searching : " . $string);
        $result = Text::search('content:(' . $string . ')')->take( 10)->raw()->getRaw();
        $hits = $result['hits']['hits'];
        if($this->option('classify')){
            $topics = [];
            foreach ($hits as $item){
                $category = $item['_source']['category'];
                if(isset( $topics[$category])){
                    $topics[$category] += $item['_score'];
                }else{
                    $topics[$category] = $item['_score'];
                }
            }
            arsort( $topics );
            dump( $topics );
            if(!$this->confirm( "Show search result ?")){
                return 0;
            }
        }
        dump( "HITS", $hits);
    }
}
