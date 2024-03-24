<?php

namespace App\Jobs;

use App\Models\Vote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreVoteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $voteData;

    /**
     * Create a new job instance.
     * @param array $voteData Data required to store the vote
     * @return void
     */
    public function __construct(array $voteData)
    {
        $this->voteData = $voteData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Vote::create([
            'user_id' => $this->voteData['user_id'],
            'item' => $this->voteData['item'],
            'user_ip' => $this->voteData['user_ip'],
            'estimated_location' => $this->voteData['estimated_location'],
        ]);
    }
}
