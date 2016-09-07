<div class="row">
    <h3 class="heading-title">
        <span class="first">{{ trans($lang_common . '.most_read_block_header') }}</span>
    </h3>
    <div class="most-read-section">
        @if($most_read_list->count() > 0)
            <ul class="most-read-list @if(request()->segment(1) == 'search') in-search-page @endif">
                @foreach($most_read_list as $mr)
                    <li class="most-item">
                        <a href="{{ route(config('frontend.ROUTE_NAME.detail_news'), ['cat_alias'=>$mr->cat_alias, 'alias'=>$mr->alias, 'id'=>$mr->id]) }}" class="title">{{ $mr->title }}</a>
                        <div class="intro">
                            {{ $mr->intro_content }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <script>
        $(function(){
            $('div.most-read-section').slimScroll({
                height: $('div.most-read-section').height()
            });
        });
    </script>
</div>