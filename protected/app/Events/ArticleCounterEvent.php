<?php   namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\NewsHitCounter;
//use App\Models\News;

class ArticleCounterEvent extends Event{
    use SerializesModels;

    public $counter;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(NewsHitCounter $counter)
    {
        $this->counter = $counter;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
