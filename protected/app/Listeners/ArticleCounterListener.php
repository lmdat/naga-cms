<?php

namespace App\Listeners;

use App\Events\ArticleCounterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticleCounterListener{
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
     * @param  ArticleCounterEvent  $event
     * @return void
     */
    public function handle(ArticleCounterEvent $event)
    {
        $timeout = config('constant.ARTICLE_HIT_COUNTER_TIMEOUT');
        $_key = $event->counter->news_id . '-article-hit-counter-expired-at';
        if(!session()->has($_key)){
            $event->counter->increment('hit_counter');
            session()->put($_key, strtotime("+".$timeout." minutes"));

        }
        else{
            if(time() >= session()->get($_key)){
                $event->counter->increment('hit_counter');
                session()->put($_key, strtotime("+".$timeout." minutes"));
            }
        }

    }
}
