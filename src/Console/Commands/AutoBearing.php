<?php
/**
 *定时计息-自定义-控制台命令 每日凌晨处理
 */
namespace Loid\Module\Lbb\Console\Commands;

use Illuminate\Console\Command;
use Loid\Module\Lbb\Logic\AutoBearing as AutoBearingLogic;

class AutoBearing extends Command
{
    /**
     * The name and signature of the console command. 
     *
     * @var string
     */
    protected $signature = 'bearing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时计息';

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
     * Execute the console command. 处理定时计息逻辑
     *
     * @return mixed
     */
    public function handle() {
        AutoBearingLogic::execute();
    }
}
