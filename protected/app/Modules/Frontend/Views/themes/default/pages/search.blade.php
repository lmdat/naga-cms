@extends('Frontend::layouts.master')


@section('content')
    <div class="row">
    {{--NEWS BLOCK--}}
    <!--NEWS BLOCK-->
    <div class="col-md-9 col-sm-9">
        <div class="row">
            <div class="col-md-9 col-sm-9">
                <div class="row">
                    {{--SEARCH RESULT--}}
                    <!--SEARCH RESULT-->
                    <section class="col-md-12 col-sm-12 item-list-section">
                        @if($search_items->count() > 0)
                        <ul class="item-list">
                            @foreach($search_items as $item)
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
                            {{--@if($search_items->count() == config('frontend.TOTAL_ITEM_PER_PAGE_IN_SEARCH'))--}}
                                {{--<div class="text-center">--}}
                                    {{--<button type="button" class="btn btn-xs btn-block">{{ trans($lang_common . '.load_more') }}</button>--}}
                                {{--</div>--}}
                            {{--@endif--}}
                            {{--{!! Form::hidden('current_page', 1) !!}--}}
                            <div class="text-center">
                                {!! $search_items->render() !!}
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

                </div>
            </div>


        </div>
    </div>

    <div class="col-md-3 col-sm-3">

    </div>
</div>
@stop