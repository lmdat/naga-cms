
var $ads_below_most_read_top = 0;
var $doc_height = 0;
var $offset_anchor = 300;
var $top_fixed = 25;

$(function(){
    $doc_height = $(document).height();
    $ads_below_most_read_top = $('.ads-below-most-read').offset().top;

    
    $(window).scroll(function(){
        //Ads Below Most Read
        if($(window).scrollTop() >= $ads_below_most_read_top){
            $('.ads-below-most-read').css({
                position: 'fixed',
                top: $top_fixed
            });

            if($(window).scrollTop() + $(window).height() >= $doc_height){
                $('.ads-below-most-read').css({
                    position: 'absolute',
                    top: $doc_height - $offset_anchor - $('.ads-below-most-read').height()
                });

            }    
        }


        if($(window).scrollTop() < $ads_below_most_read_top){
             $('.ads-below-most-read').css({
                position: 'static',
                top: $ads_below_most_read_top
            });
        }
    });
    
});