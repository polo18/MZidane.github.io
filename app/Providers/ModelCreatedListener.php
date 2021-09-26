<?php

namespace App\Providers;

use App\Providers\ModelCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ModelCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ModelCreated  $event
     * @return void
     */
    public function handle(ModelCreated $event)
    {
        //
    }
}
