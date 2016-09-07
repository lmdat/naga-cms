@extends('Frontend::layouts.master')


@section('content')
    <div class="row">
    {{--NEWS BLOCK--}}
    <!--NEWS BLOCK-->
    <div class="col-md-9 col-sm-9">
        <div class="row">
            {{--CAT LIST--}}
            <!--CAT LIST-->
            <div class="col-md-9 col-sm-9">
                {{--CAT HIGHLIGHT--}}
                <!--CAT HIGHLIGHT-->
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <h3 class="heading-title">
                            <span class="first"><a href="{{ route(config('frontend.ROUTE_NAME.category'), ['cat_alias'=>$cat->alias]) }}">{{ $cat->cat_name }}</a></span>
                            <?php $k = 0;?>
                            @foreach($cat_siblings as $sib)
                                <span class="next"><a href="{{ route('news-category', ['cat_alias'=>$sib->alias]) }}">{{ $sib->cat_name }}</a></span>
                                @if($k++ < $cat_siblings->count() - 1)
                                    <span class="separate">|</span>
                                @endif
                            @endforeach
                        </h3>
                    </div>
                    @if(isset($highlight))
                    <section class="col-md-12 col-sm-12 cat-highlight-item">
                        <div class="cat-highlight-title">
                            <a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$highlight->cat_alias, 'alias'=>$highlight->alias, 'id'=>$highlight->id]) }}">{{ $highlight->title }}</a>
                        </div>
                        <div class="thumbnail thumbnail-image">
                            <a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$highlight->cat_alias, 'alias'=>$highlight->alias, 'id'=>$highlight->id]) }}"><img src='{{ $highlight->parseFeaturedImageUrl() }}' /></a>
                            <div class="overlay-caption">
                                <div class="intro cat-highlight-intro">
                                    {{ $highlight->intro_content }}
                                </div>
                            </div>
                        </div>

                        @if($hl_relations->count() > 0)
                        <div class="relation-list">
                            <ul>
                                @foreach($hl_relations as $rl)
                                <li><a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$rl->cat_alias, 'alias'=>$rl->alias, 'id'=>$rl->id]) }}"><i class="fa fa-angle-double-right"></i> {{ $rl->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </section>
                    @endif

                    {{--ITEM LIST--}}
                    <!--ITEM LIST-->
                    <section class="col-md-12 col-sm-12 item-list-section">
                        @if($col_news->count() > 0)
                        <ul class="item-list">
                            @foreach($col_news as $item)
                                @if($item->id == $highlight->id)
                                    @continue
                                @endif
                                {{--ITEM--}}
                                <li class="item-row">
                                    <div class="item-container">
                                        <div class="featured-image pull-left">
                                            <a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$item->cat_alias, 'alias'=>$item->alias, 'id'=>$item->id]) }}"><img src='{{ $item->parseFeaturedImageUrl() }}' /></a>
                                        </div>
                                        <div class="item-content">
                                            <div class="item-title">
                                                <a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$item->cat_alias, 'alias'=>$item->alias, 'id'=>$item->id]) }}">{{ $item->title }}</a>
                                            </div>
                                            <div class="item-intro">
                                                {{ $item->intro_content }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                            {{--@if($col_news->count() == config('frontend.TOTAL_ITEM_PER_PAGE_IN_CATEGORY'))--}}
                                {{--<div class="text-center">--}}
                                    {{--<button type="button" class="btn btn-xs btn-block">{{ trans($lang_common . '.load_more') }}</button>--}}
                                    {{----}}
                                {{--</div>--}}
                            {{--@endif--}}
                            {{--{!! Form::hidden('current_page', 1) !!}--}}
                            <div class="text-center">
                                {!! $col_news->render() !!}
                            </div>
                        @endif
                    </section>
                </div>
            </div>

            {{--MOST READ--}}
            <!--MOST READ-->
            <div class="col-md-3 col-sm-3">
                {!! \App\Modules\Frontend\Partials\MostReadPartial::render() !!}

                {{--ADS BELOW MOST READ--}}
                <div class="row">
                    <div class="ads-below-most-read">
                        @if(isset($ads_positions[config('constant.ADS_POSITION.CATEGORY_FIX.CATEGORY_BELOW_MOST_READ')]))
                            <?php
                            $pos = $ads_positions[config('constant.ADS_POSITION.CATEGORY_FIX.CATEGORY_BELOW_MOST_READ')];
                            $ads_list = $col_ads->where('pos_id', $pos->id);
                            ?>
                            @if($ads_list->count() > 0)
                                @foreach($ads_list as $ads)
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            {!! $ads->ads_content !!}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <img data-src="holder.js/100px400?text=Ads Space 4"/>
                            @endif
                        @endif

                    </div>
                    <script src="{{ asset(Theme::url('js/ads_scroll.js')) }}"></script>
                </div>
            </div>


        </div>
    </div>

    {{--ADS RIGHT SECTION--}}
    <!--ADS RIGHT SECTION-->
    <div class="col-md-3 col-sm-3">
        @if(isset($ads_positions[config('constant.ADS_POSITION.CATEGORY_FIX.CATEGORY_RIGHT_BAR')]))
            <?php
            $pos = $ads_positions[config('constant.ADS_POSITION.CATEGORY_FIX.CATEGORY_RIGHT_BAR')];
            $ads_list = $col_ads->where('pos_id', $pos->id);
            ?>
            @if($ads_list->count() > 0)
                @foreach($ads_list as $ads)
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            {!! $ads->ads_content !!}
                        </div>
                    </div>
                    <hr/>
                @endforeach
            @else
                <img data-src="holder.js/100px400?text=Ads Space 1"/>
                <hr>
                <img data-src="holder.js/100px400?text=Ads Space 2"/>
                <hr>
                <img data-src="holder.js/100px400?text=Ads Space 3"/>
            @endif
        @endif

    </div>
</div>
@stop