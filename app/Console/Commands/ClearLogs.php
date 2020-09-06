<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLogs extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'logs:clear';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Clear all logs';

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
    exec('truncate -s 0 ' . storage_path('logs/*.log'));

    $this->comment('Logs have been cleared!');
  }
}
