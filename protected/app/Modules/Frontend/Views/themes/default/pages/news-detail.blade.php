@extends('Frontend::layouts.master')


@section('content')
    <div class="row">
        <div class="col-md-9 col-sm-9">
            <div class="row">

                <div class="col-md-9 col-sm-9">
                    @if($breadcrumbs->count() > 0)
                    <div class="row breadcrumbs">
                        <div class="col-md-12 col-sm-12">
                            @for($k=0; $k < $breadcrumbs->count(); $k++)
                                <a href="{{ route(config('frontend.ROUTE_NAME.category'), ['cat_alias'=>$breadcrumbs[$k]->alias]) }}" @if($k == $breadcrumbs->count() - 1) class="last" @endif>{{ $breadcrumbs[$k]->cat_name }}</a>
                                @if($k < $breadcrumbs->count() - 1)
                                    <span class="seperator"><i class="fa fa-angle-double-right"></i></span>
                                @endif
                            @endfor
                        </div>
                    </div>
                    @endif
                    {{--ARTICLE SECTION--}}
                    <!--ARTICLE SECTION-->
                    <div class="row">
                        <section class="col-md-12 col-sm-12 detail-section">

                            <div class="row detail-toolbar">
                                <div class="col-md-6 col-sm-6">

                                </div>
                                <div class="col-md-6 col-sm-6 text-right">
                                    {{ $article_datetime }}
                                </div>
                            </div>

                            <div class="detail-title">
                                {{ $article->title }}
                            </div>

                            <div class="detail-intro">
                                <i class="fa fa-quote-left"></i>
                                <span>{{ $article->intro_content }}</span>
                            </div>

                            <div class="detail-content">
                                {!! $article->detail->main_content !!}
                            </div>

                            <div class="tags">
                                @if(count($tags) > 0)
                                    <label>{{ trans($lang_mod . '.keyword') }}</label>
                                    @foreach($tags as $tag)
                                        <a href="#"><span class="label label-default">{{ $tag }}</span></a>
                                    @endforeach
                                @endif
                            </div>

                            <div class="relation-list">
                                @if($article_relations->count() > 0)
                                <h3 class="heading-title">
                                    <span class="first">Tin Liên Quan</span>
                                </h3>
                                <ul>
                                    @foreach($article_relations as $rl)
                                    <li><a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$rl->cat_alias, 'alias'=>$rl->alias, 'id'=>$rl->id]) }}"><i class="fa fa-angle-double-right"></i> {{ $rl->title }}</a></li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </section>
                    </div>

                    {{--ADS BELOW DETAIL--}}
                    <div class="row">
                        <section class="col-md-12 col-sm-12 ads-below-detail">
                            @if(isset($ads_positions[config('constant.ADS_POSITION.DETAIL_NEWS_FIX.DETAIL_NEWS_BELOW_RELATION')]))
                                <?php
                                $pos = $ads_positions[config('constant.ADS_POSITION.DETAIL_NEWS_FIX.DETAIL_NEWS_BELOW_RELATION')];
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
                                     <img data-src="holder.js/100px200?text=Ads Space 10"/>
                                @endif
                            @endif
                        </section>
                    </div>

                </div>

                {{--MOST READ--}}
                <div class="col-md-3 col-sm-3">
                    {!! \App\Modules\Frontend\Partials\MostReadPartial::render() !!}

                    {{--ADS BELOW MOST READ--}}
                    <div class="row">
                        <div class="ads-below-most-read">
                            @if(isset($ads_positions[config('constant.ADS_POSITION.DETAIL_NEWS_FIX.DETAIL_NEWS_BELOW_MOST_READ')]))
                                <?php
                                $pos = $ads_positions[config('constant.ADS_POSITION.DETAIL_NEWS_FIX.DETAIL_NEWS_BELOW_MOST_READ')];
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
        <div class="col-md-3 col-sm-3">
            @if(isset($ads_positions[config('constant.ADS_POSITION.DETAIL_NEWS_FIX.DETAIL_NEWS_RIGHT_BAR')]))
                <?php
                $pos = $ads_positions[config('constant.ADS_POSITION.DETAIL_NEWS_FIX.DETAIL_NEWS_RIGHT_BAR')];
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