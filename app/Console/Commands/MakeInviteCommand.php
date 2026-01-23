<?php

namespace App\Console\Commands;

use App\Services\Auth\Invite\InviteServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class MakeInviteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:invite {limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var InviteServiceInterface $authService */
        $authService = App::make(InviteServiceInterface::class);

        $invite = $authService->createInvite((int) $this->argument('limit'));

        $this->info("Invite #{$invite->id} created with {$invite->limit} limits");
        $this->info('Link: '.url("/registration/{$invite->hash}"));
    }
}
