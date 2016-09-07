<section class="container header-section">
    <div class="row">
        <div class="col-md-4 col-sm-4">
            <img data-src="holder.js/150x60?text=Logo"/>
        </div>
        <div class="col-md-8 col-sm-8 text-right hidden-xs">

            @if(isset($ads_positions[$right_above_menu]))
                <?php
                    $pos = $ads_positions[$right_above_menu];
                    $ads_list = $col_ads->where('pos_id', $pos->id);
                ?>
                @if($ads_list->count() > 0)
                    {!! $ads_list->random()->ads_content !!}
                @else
                    <img data-src="holder.js/456x80?text=Ads Space 456x80"/>
                @endif

            @endif
        </div>
    </div>
</section>