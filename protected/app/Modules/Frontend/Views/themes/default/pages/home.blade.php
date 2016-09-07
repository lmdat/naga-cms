@extends('Frontend::layouts.master')

@section('content')
{{--FEATURED SECTION--}}
<!--FEATURED SECTION-->

<section class="featured-section">
    <div class="row">
        {{--HIGHLIGHT SECTION--}}
        <!--HIGHLIGHT SECTION-->
        <div class="col-md-9 col-sm-9 highlight-section">
            <div class="row">
                {{--HIGHLIGHT--}}
                <!--HIGHLIGHT-->
                <div class="col-md-8 col-sm-8">
                @if(count($highlights) > 0)
                    <div class="row">
                        <div class="col-md-12 col-sm-12 highlight-item">
                            <a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$highlights[0]->cat_alias, 'alias'=>$highlights[0]->alias, 'id'=>$highlights[0]->id]) }}" class="caption-a">
                                <div class="thumbnail thumbnail-image">
                                    <img src='{{ Theme::url($highlights[0]->parseFeaturedImageUrl()) }}' />
                                    <div class="overlay-caption">
                                        <div class="title">{{ $highlights[0]->title }}</div>
                                        <span class="intro">{{ $highlights[0]->intro_content }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 highlight-others">
                            <div class="row">
                            @for($i=1;$i<count($highlights);$i++)
                                <div class="col-md-4 col-sm-4">
                                    <a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$highlights[$i]->cat_alias, 'alias'=>$highlights[$i]->alias, 'id'=>$highlights[$i]->id]) }}">
                                        <div class="thumbnail">
                                            <img src='{{ Theme::url($highlights[$i]->parseFeaturedImageUrl()) }}' />
                                            <div class="caption">
                                                <small>{{ $highlights[$i]->title }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endfor
                            </div>
                        </div>
                    </div>
                @endif
                </div>

                {{--HOT--}}
                <!--HOT-->
                <div class="col-md-4 col-sm-4">
                    <h3 class="heading-title">
                        <span class="first">{{ trans($lang_mod . '.hot_news_block_header') }}</span>
                    </h3>
                    <div class="hot-section">
                        <ul class="hot-list">
                        @foreach($hots as $hot)
                            <li class="hot-item">
                                <a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$hot->cat_alias, 'alias'=>$hot->alias, 'id'=>$hot->id]) }}">
                                    <span>{{ $hot->title }}</span>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    <script>
                        $(function(){
                            $('div.hot-section').slimScroll({
                                height: $('div.hot-section').height()
                            });
                        });
                    </script>
                </div>
            </div>
        </div>

        {{--RIGHT ADS SECTION--}}
        <!--RIGHT ADS SECTION-->
        <div class="col-md-3 col-sm-3 right-ads-section hidden-xs">
            @if(isset($ads_positions[config('constant.ADS_POSITION.HOME_FIX.HOME_RIGHT_HIGHLIGHT')]))
                <?php
                    $pos = $ads_positions[config('constant.ADS_POSITION.HOME_FIX.HOME_RIGHT_HIGHLIGHT')];
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
                    <img data-src="holder.js/100px300?text=Ads Space"/>
                @endif
            @endif
        </div>
    </div>
</section>


@for($i=0;$i<$col_category->count();$i+=$cat_group)
    {{--CATEGORY SECTION--}}
    <!--CATEGORY SECTION-->
    <section class="category-section">
        <div class="row">
            <div class="col-md-9 col-sm-9">
                @for($j=$i;$j<($i + $cat_group);$j++)
                    {{--CATEGORY BLOCK--}}
                    <!--CATEGORY BLOCK-->
                    <div class="category-block">
                        <div class="row">
                            {{--HEADER BLOCK--}}
                            <!--HEADER BLOCK-->
                            <div class="col-md-12 col-sm-12">
                                <h3 class="heading-title">
                                    <span class="first"><a href="{{ route(config('frontend.ROUTE_NAME.category'), ['cat_alias'=>$col_category[$j]->alias]) }}">{{ $col_category[$j]->cat_name }}</a></span>
                                    <?php $k = 0;?>
                                    @foreach($col_category[$j]->children as $child)
                                        <span class="next"><a href="{{ route('news-category', ['cat_alias'=>$child->alias]) }}">{{ $child->cat_name }}</a></span>
                                        @if($k++ < $col_category[$j]->children->count() - 1)
                                            <span class="separate">|</span>
                                        @endif
                                    @endforeach
                                </h3>
                            </div>

                            {{--FIRST ITEM--}}
                            <!--FIRST ITEM-->
                            <div class="col-md-7 col-sm-7">
                                @if($col_category[$j]->news_items->count() > 0)
                                    <a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$col_category[$j]->news_items[0]->cat_alias, 'alias'=>$col_category[$j]->news_items[0]->alias, 'id'=>$col_category[$j]->news_items[0]->id]) }}" class="caption-a">
                                        <div class="thumbnail thumbnail-image">
                                            <img src="{{ Theme::url($col_category[$j]->news_items[0]->parseFeaturedImageUrl()) }}" />
                                            <div class="overlay-caption overlay-category-section">
                                                <div class="title">
                                                    {{ $col_category[$j]->news_items[0]->title }}
                                                </div>
                                                <span class="intro">{{ $col_category[$j]->news_items[0]->intro_content }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>

                            {{--OTHERS ITEM--}}
                            <!--OTHERS ITEM-->
                            <div class="col-md-5 col-sm-5">
                                @if($col_category[$j]->news_items->count() > 1)
                                    <ul class="category-item-list">
                                        @for($u=1;$u<$col_category[$j]->news_items->count();$u++)
                                            <?php $item = $col_category[$j]->news_items[$u]?>
                                            <li class="category-item">
                                                <a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$item->cat_alias, 'alias'=>$item->alias, 'id'=>$item->id]) }}">
                                                    <div class="item-container">
                                                        <div class="thumbnail-box">
                                                            <img src='{{ Theme::url($item->parseFeaturedImageUrl()) }}' />
                                                        </div>
                                                        <div class="item-title">
                                                            {{ $item->title }}
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endfor
                                    </ul>
                                @endif
                            </div>

                        </div>
                    </div>
                @endfor

            </div>

            {{--RIGHT ADS SECTION--}}
            <!--RIGHT ADS SECTION-->

            <div class="col-md-3 col-sm-3 right-ads-section hidden-xs">
                @if(isset($ads_positions[config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_RIGHT_CATEGORY_GROUP') . (($i/$cat_group)+1)]))
                    <?php
                    $pos = $ads_positions[config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_RIGHT_CATEGORY_GROUP') . (($i/$cat_group)+1)];
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
                        <img data-src="holder.js/100px670?text=Ads Space"/>
                    @endif
                @endif

            </div>
        </div>
    </section>

    @if(isset($ads_positions[config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_FULL_BELOW_CATEGORY_GROUP') . (($i/$cat_group)+1)]))
    {{--FULL ADS SECTION--}}
    <!--FULL ADS SECTION-->
    <section class="full-ads-section">
        <?php
        $pos = $ads_positions[config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_FULL_BELOW_CATEGORY_GROUP') . ($i/$cat_group+1)];
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
            <img data-src="holder.js/100px90?text=Ads Space"/>
        @endif
    </section>
    @endif
@endfor
@stop