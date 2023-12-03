<?php

namespace App\Events\Tugas ;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TugasUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($tugas, $new_permissions = [], $old_permissions = [])
    {
        $this->tugas = $tugas;
        $this->new_permissions = $new_permissions;
        $this->old_permissions = $old_permissions;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
