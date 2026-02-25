<?php

namespace App\Console\Commands;

use App\Services\MemberStatusService;
use Illuminate\Console\Command;

class UpdateMemberStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(MemberStatusService $service) : int
    {
        $service->updateStatuses();

        $this->info('Member statuses updated sucessfully.');

        return self::SUCCESS;
    }
}
