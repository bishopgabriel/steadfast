<?php

namespace App\Console\Commands;

use App\Models\Vote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\VoteTotals;

class ProcessVotesAndSendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vote:count-votes-and-send-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count votes and send email with totals.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Vote::select('item')->distinct()->get();

        $scores = [];
        foreach ($items as $item) {
            $scores[$item->item] = Vote::where('item', $item->item)->count();
        }

        $recipientEmail = env('VOTE_EMAIL_RECIPIENT', 'dev@steadfastcollective.com');
        Mail::to($recipientEmail)->send(new VoteTotals($scores));

        $this->info('Email sent with vote totals.');
    }
}
