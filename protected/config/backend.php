<?php
return [
    'media' => [
        'css' => [
            'media/lib/font-awesome/css/font-awesome.min.css',
            'media/lib/bootstrap/dist/css/bootstrap.min.css',
            'media/lib/iCheck/skins/minimal/_all.css',
            'media/lib/summernote/dist/summernote.css',
            'media/lib/animate.css/animate.min.css',
            'media/lib/select2/dist/css/select2.min.css',
            'media/lib/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css',
            'media/lib/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
            'media/lib/bootstrap-daterangepicker/daterangepicker.css',

            'media/lib/Progress.js/minified/progressjs.min.css',
            
            'media/lib/bootstrap-tagsinput/dist/bootstrap-tagsinput.css',

            'media/local/css/google-font.css',
            'media/local/css/AdminLTE.min.css',
            'media/local/css/skins/skin-green.min.css',

            //'media/local/css/skins/skin-red.min.css',

            //'media/lib/jquery-ui/themes/ui-lightness/jquery-ui.min.css',

            'media/local/vendors/vii-uploader/vii-uploader.css',
            'media/local/vendors/nestable/nestable.css',
            

            'media/local/css/main.css'

        ],

        'js' => [
            'media/lib/jquery/dist/jquery.min.js',
            'media/lib/bootstrap/dist/js/bootstrap.min.js',
            'media/lib/select2/dist/js/select2.full.min.js',
            'media/lib/bootstrap-switch/dist/js/bootstrap-switch.min.js',
            //'media/lib/moment/min/moment-with-locales.min.js',
            'media/lib/moment/min/moment.min.js',
            'media/lib/moment/locale/vi.js',
            'media/lib/iCheck/icheck.min.js',
            'media/lib/summernote/dist/summernote.min.js',
            //'media/local/js/jquery.slimscroll.min.js',
            'media/lib/jquery-slimscroll/jquery.slimscroll.min.js',
            //'media/local/js/url.min.js',
            'media/lib/html.sortable/dist/html.sortable.min.js',

            'media/lib/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
            'media/lib/bootstrap-daterangepicker/daterangepicker.js',
            //'media/lib/jquery-ui/ui/minified/core.js',
            //'media/lib/jquery-ui/ui/minified/datepicker.js',

            //'media/local/vendors/ckeditor/ckeditor.js',
            
            'media/lib/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js',


            'media/local/vendors/tinymce/tinymce.min.js',
            'media/local/vendors/tinymce/jquery.tinymce.min.js',

            'media/lib/Progress.js/minified/progress.min.js',
            'media/local/vendors/vii-uploader/jquery.vii-uploader.js',
            'media/local/vendors/nestable/jquery.nestable.js',
            
            'media/local/js/app.min.js',

        ],

        'layout' => [
            'skin-color' => 'green'
        ],
    ],

    'news_status' => [
        'NEWS_DRAFT_STATUS' => 'draft_status',
        'NEWS_PENDING_STATUS' => 'pending_view_status',
        'NEWS_PUBLISHED_STATUS' => 'published_status',
        'NEWS_AUTO_PUBLISHED_STATUS' => 'auto_published_status',
        'NEWS_UNPUBLISHED_STATUS' => 'unpublished_status',
    ],

    'news_status_label_color' => [
        'NEWS_DRAFT_STATUS' => 'bg-navy',
        'NEWS_PENDING_STATUS' => 'label-warning',
        'NEWS_PUBLISHED_STATUS' => 'bg-olive',
        'NEWS_AUTO_PUBLISHED_STATUS' => 'bg-purple',
        'NEWS_UNPUBLISHED_STATUS' => 'label-default',
    ],


    'news_type' => [
        'NEWS_STANDARD_TYPE' => [
            'fa fa-newspaper-o',
            'standard_type'
        ],
        
        'NEWS_VIDEO_TYPE' => [
            'fa fa-video-camera',
            'video_type'
        ]
    ],

    'rows_per_page' =>[
        '20' => 20,
        '30' => 30,
        '50' => 50
    ],

    'file_uploader' => [
        'USE_CDN' => false,

        'FTP' => [
            'FTP_1' => [
                'host' => '',
                'port' => 21,
                'username' => '',
                'password' => '',
                'ssl' => false,
                'passive' => false,
                'timeout' => 90
            ],

            'FTP_2' => [
                'host' => '',
                'port' => 21,
                'username' => '',
                'password' => '',
                'ssl' => false,
                'passive' => false,
                'timeout' => 90
            ]
        ],

        'CDN' => [
            'CDN_1' =>[
                'host' => '',
                'file_path' => '',
                'root_url' => ''
            ],
        ],

        'ROOT_PATH' => 'E:\Server\www\zoyocms',
        'LOCAL_PATH_IMAGE' => 'storage/images',
        'LOCAL_PATH_FILE' => 'storage/files',
    ],


    'lang' => 'vi',
    
    'start_year' => '2016',
    
    'guid_key' => '4jK49Av5MeQ=',



];