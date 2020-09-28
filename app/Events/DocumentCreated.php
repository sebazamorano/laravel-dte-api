<?php

namespace App\Events;

use App\Models\Documento;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Information about the created document.
     *
     * @var object
     */
    public $document;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Documento $documento)
    {
        $this->document = $documento;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $users = $this->document->empresa->companyUsers();
        return new PrivateChannel('App.User.1');
    }
}
