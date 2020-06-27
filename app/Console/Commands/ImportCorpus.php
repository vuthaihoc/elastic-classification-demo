<?php

namespace App\Console\Commands;

use App\Models\Text;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ImportCorpus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import {--s|sync : Search Syncing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import dataset';

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
        Text::truncate();// Xoá bảng text
        Text::unguard();// Cho phép insert trường ID
        if(!$this->option('sync')){
            Text::disableSearchSyncing();// Tạm ngừng index để insert nhanh hơn
        }
        foreach ($this->getText() as $k => $text){
            if(preg_match( "/\,([^\,]+)$/", $text, $matches)){
                $category = trim($matches[1]);
            }else{
                continue;
            }
            $content = mb_substr( $text, 2, -(mb_strlen( $category )) -2);
            $content = trim( $content, "\'\"");
            Text::updateOrCreate([
                'id' => $k + 1,],
                [
                'content' => $content,
                'category' => $category,
            ]);
            if($k%100 == 0){
                echo ".";
            }
        }
        dump(Text::count());
        if(!$this->option('sync')){
            Text::enableSearchSyncing();
        }
        Artisan::call( 'scout:import', ['model' => Text::class], $this->getOutput());
    }
    
    protected function getText(){
        $file = storage_path('dataset.csv');
        $fh = fopen( $file, 'r');
        $line = fgets( $fh);
        while (!feof( $fh) && $line = fgets( $fh)){
            yield $line;
        }
    }
    
}
