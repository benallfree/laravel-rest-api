<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecalcCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:recalc {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalc all model values';

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
     * @return mixed
     */
    public function handle()
    {
      $class = $this->argument('model');
      $this->info("Recalculating $class");
      call_user_func("App\\{$class}::query")->chunk(50, function($objs) {
        foreach($objs as $obj)
        {
          $this->info(sprintf("Calculating ID %d", $obj->id));
          $obj->recalc(true);
        }
      });
    }
}
