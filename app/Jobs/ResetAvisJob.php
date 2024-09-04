<?php

namespace App\Jobs;

use App\Models\Avis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ResetAvisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        cache::forget('allavis');
        Cache::forget('Newavis');
        // 'mypubs-'.Auth::user()->id
        cache::forget('mypubs-'.$this->user->id);
        for ($i=0; $i <= 50; $i++) { 
            $key = 'allpubs-'.$i;
            if (Cache::has($key)) {
                Cache::forget($key);
            } else {
                break;
            }
        }


        $avis =  Cache::rememberForever('allavis', function() {
            return Avis::orderBy('id', 'DESC')->get();
        });

    }
}
