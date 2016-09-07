<?php
return [

    'setting_create_header' => 'Tạo Setting',
    'setting_edit_header' => 'Cập Nhật Setting',
    'fb_id' => "Facebook's App ID",
    'fb_page' => "Facebook's Page",
    'fb_page_id' => "Facebook's Page ID",
    'ga_id' => 'Google Analytics ID',
    'custom_analytics_script' => 'Custom Analytics Script',
    'site_name' => 'Tên Website',
    'default_seo_image' => 'Ảnh Mặc Định Khi Chia Sẻ',
    'theme' => 'Theme',
    'website_on_off' => 'Tắt/Mở Website',
    'website_off_message' => 'Thông Báo Khi Tắt Website',
    'website_off_default_message' => 'Website Tạm Thời Ngưng Hoạt Động Để Bảo Trì.',
    'website_default_description' => 'Mô Tả Website',

     
     //Form Validate Message
    'fb_id_validate' => [
        'required' => '[:field] không được để trống.',
        'numeric' => '[:field] phải là số.'
    ],

    'fb_page_validate' => [
        'url' => '[:field] url không hợp lệ',
    ],

    'fb_page_id_validate' => [
        'numeric' => '[:field] phải là số.',
    ],
    
    'ga_id_validate' => [
        'required' => '[:field] không được để trống.',
        'numeric' => '[:field] phải là số.'
    ],

    'site_name_validate' => [
        'required' => '[:field] không được để trống.',
    ],    

    'default_seo_image_validate' => [
        'image' => '[:field] phải là file hình ảnh.',
    ],

    //Error Message
    'setting_error' => 'Có lỗi xảy ra! Không thể tạo mới/cập nhật.',
    'setting_success' => 'Đã cập nhật.',

];