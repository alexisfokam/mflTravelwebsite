<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\Avis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAvisNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $avis;

    /**
     * Create a new job instance.
     */
    public function __construct($avis)
    {
        $this->avis = $avis;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Récupérer tous les utilisateurs (ou un sous-ensemble, selon vos besoins)
        $users = User::where('type', 1)->get(); // Exclure l'utilisateur qui a créé l'avis

        foreach ($users as $user) {
            $user->notify(new Avis($this->avis));
        }
    }
}
