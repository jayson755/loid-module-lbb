<?php
/**
 *定时计息-自定义-控制台命令 处理定存宝到期计息
 */
namespace Loid\Module\Lbb\Console\Commands;

use Illuminate\Console\Command;
use Loid\Module\Lbb\Logic\AutoRedemption as AutoRedemptionLogic;

class AutoRedemption extends Command
{
    /**
     * The name and signature of the console command. 
     *
     * @var string
     */
    protected $signature = 'redemption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时定存宝到期计息';

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
        AutoRedemptionLogic::execute();
    }
}
