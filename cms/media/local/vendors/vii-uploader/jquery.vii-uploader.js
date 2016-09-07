/*@preserve
 * Description:
 *  jQuery plugin for html5 dragNdrop files to upload from desktop to browser
 *
 * Author: Le Minh Dat  |   Web Developer 
 *
 * Email: minh_dat_le@yahoo.com
 * 
 * Copyright (c) 2015 
 * 
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project Name:
 *   Vii Uploader
 *
 * Version:  0.1.1
 *
 * Features:
 *
 *
 * Usage:
 * 	
 *
 */

"use strict";



(function($){

    $.ViiUploader = function(nid, element, opts){
        
        var defaults = {
            accept              :   '*', // *|image/*,video/*
            width               :   '100%',
            height              :   400,
            displayMode         :   'modal', //modal, inline
            multipleUpload      :   true,
            multipleChoice      :   true,
            borderColor         :   'steelblue',
            btnOpenModalClass   :   'btn btn-xs btn-success',
            btnOpenModalText    :   '<i class="fa fa-photo"></i> Insert Media',
            maxFiles            :   10,
            maxFileSize         :   2, //MB
            coverUpload         :   'images/cover-upload.png',
            uploadUrl           :   'upload.php',
            getImagesUrl        :   'get_images.php',
            params              :   {},
            fileUploadName      :   'vii-uploader-file',
            csrfMetaName        :   '', //set false if not use csrf
            insertImage         :   function(files, from_url){},
            startUpload         :   function(file, index, count){},
            finishUpload        :   function(file, index, count){},
            finishAllUpload     :   function(){}
        };

        var mimeType = {
            "application/msword" : "doc",
            "application/vnd.ms-excel" : "xls",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" : "xlsx",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document" : "docx",
            "text/plain" : "txt",
            "application/pdf": "pdf",
            "image/png" : "png",
            "image/jpeg" : "jpg",
            "image/gif" : "gif",
            "video/mp4" : "mp4",
            "audio/mpeg3" : "mp3",
            "audio/x-mpeg-3" : "mp3",
            "video/x-flv" : "flv"
        };

        var plugin = $(this);

        plugin.options = $.extend({}, defaults, opts);

        var $element = $(element);

        //The files that are uploaded
        var file_uploads = {};

        //Total files uploads
        var file_uploads_count = 0;

        //Total files uploaded
        var file_uploaded_count = 0;

        //The files in library
        var files = {};

        var files_count = 0;

        //The files have been selected by user
        var selected_files = {};
        var selected_url = {};

        /*----------------------------------------------------------------*/

        var uploadManager = $("<div id='upload_manager_"+nid+"' class='vii-uploader'></div>");

        var fileManager = $("<div id='file_manager_"+nid+"' class='vii-media'></div>");

        var urlManager = $("<div id='url_manager_"+nid+"' class='vii-url'></div>");


        var fileUploadList = $("<div id='file_upload_list_"+nid+"' class='container-fluid file-upload-list'></div>");
        

        var mediaList = $("<div id='media_list_"+nid+"' class='container-fluid media-list'></div>");
        
        var mediaFileInfo = $("<div id='media_file_info_"+nid+"' class='container-fluid vii-media-file-info'></div>");

        
        var urlInputContainer = $("<div id='url_input_container_"+nid+"' class='container-fluid url-input-container'></div>");
        
        var urlInput = $("<input id='url_"+nid+"' type='text' class='form-control' value='http://' />");

        var urlImagePreview = $("<img id='url_image_preview_"+nid+"' class='url-image-preview' src='' style='display: none;' />");

        var urlInfoContainer = $("<div id='url_info_container_"+nid+"' class='container-fluid vii-url-info-container'></div>");


        var btnSelectFile = $("<button id='btn_select_file_"+nid+"' type='button' class='btn btn-sm btn-info'><i class='fa fa-plus'></i> Select...</button>");

        var btnUploadFile = $("<button id='btn_upload_file_"+nid+"' type='button' class='btn btn-sm btn-success' disabled><i class='fa fa-upload'></i> Upload</button>");

        var btnClearFile = $("<button id='btn_clear_file_"+nid+"' type='button' class='btn btn-sm btn-warning' disabled><i class='fa fa-eraser'></i> Clear All</button>");

        var buttonUploadBar = $("<div id='btn_upload_bar_"+nid+"' class='container-fluid button-upload-bar'></div>");

        var infoUploadText = $([
            "<p id='info_upload_text_"+nid+"' class='info-text'>",
            "   <span>Number of files per upload: <strong>" + plugin.options.maxFiles + "</strong></span> | ",
            "   <span>Maximum size per file: <strong>" + plugin.options.maxFileSize + " MB</strong></span>",
            "</p>"
        ].join(""));


        var introUploadText = $("<div id='intro_upload_text_"+nid+"' class='intro-upload-text center-position'>DROP FILES HERE</div>");

        var navTab = $([
            "<div id='vii_tab_container_" + nid + "' class='vii-tab'>",
            "   <ul id='vii_tab_"+nid+"' class='nav nav-tabs' role='tablist'>",
            "       <li role='presentation' class='active'><a href='#tab_media_"+nid+"' aria-controls='tab_media_"+nid+"' role='tab' data-toggle='tab'>File List</a></li>",
            "       <li role='presentation' class=''><a href='#tab_upload_"+nid+"' aria-controls='tab_upload_"+nid+"' role='tab' data-toggle='tab'>Uploader</a></li>",
            "       <li role='presentation' class=''><a href='#tab_url_"+nid+"' aria-controls='tab_upload_"+nid+"' role='tab' data-toggle='tab'>Insert From URL</a></li>",
            "   </ul>",
            "   <div class='tab-content'>",
            "       <div class='tab-pane fade in active' id='tab_media_"+nid+"'>",
            "           <div class='row'>",
            "               <div class='col-md-9 media_content'>",
            "               </div>",
            "               <div class='col-md-3 media_content_info'>",
            "               </div>",
            "           </div>",
            "       </div>",
            "       <div class='tab-pane fade in' id='tab_upload_"+nid+"'>",
            "           <div class='row'>",
            "               <div class='col-md-12 upload_content'>",
            "               </div>",
            "           </div>",
            "       </div>",
            "       <div class='tab-pane fade in' id='tab_url_"+nid+"'>",
            "           <div class='row'>",
            "               <div class='col-md-9 url_content'>",
            "               </div>",
            "               <div class='col-md-3 url_content_info'>",
            "               </div>",
            "           </div>",
            "       </div>",
            "   </div>",
            "</div>"
        ].join(''));


        var modal = $([
            "<div id='vii_uploader_" + nid + "' class='vii-uploader-modal'>",
            "   <div class='modal-content'>",
            "       <div class='modal-header'>",
            "           <button type='button' class='close' aria-label='Close'>",
            "               <span aria-hidden='true'>&times;</span>",
            "           </button>",
            "           <h4 class='modal-title'>Media</h4>",
            "       </div>",
            "       <div class='modal-body'>",
            "       </div>",
            "       <div class='modal-footer'>",
            "       </div>",
            "   </div>",
            "</div>"
        ].join(''));

        var btnOpenModal = $("<button id='btn_open_modal_"+nid+"' type='button' class='"+plugin.options.btnOpenModalClass+"'>"+plugin.options.btnOpenModalText+"</button>");

        var btnInsertMedia = $("<button id='btn_insert_media_"+nid+"' type='button' class='btn btn-primary' disabled>Insert</button>");
        
        var inputFile = $("<input type='file' id='vii_file_upload_"+nid+"' />");;

        plugin.init = function(){
            
            initUI();
            
            //dragNDrop
            uploadManager.on('dragenter', function(e){

                e.stopPropagation();
                e.preventDefault();
                $(this).css('border-color', 'red');

            }).on('dragleave', function(e){

                e.stopPropagation();
                e.preventDefault();
                $(this).css('border-color', plugin.options.borderColor);

            }).on('dragover', function(e){

                e.stopPropagation();
                e.preventDefault();

            }).on('drop', function(e){
                //var files = e.dataTransfer.files;
                e.preventDefault();
                $(this).css('border-color', plugin.options.borderColor);

                if(plugin.options.multipleUpload == true){
                    mergeUploadFiles(e.originalEvent.dataTransfer.files);
                    loadUploadFiles();
                }
                
            });

            //Prevent drop files to outside the uploader div
            $(document).on('dragenter', function(e){

                e.stopPropagation();
                e.preventDefault();

            }).on('dragover', function(e){

                e.stopPropagation();
                e.preventDefault();

            }).on('drop', function(e){

                e.stopPropagation();
                e.preventDefault();

            });

            btnSelectFile.on('click', function(){
                inputFile.trigger('click');
            });

            btnUploadFile.on('click', function(){

                fileUploadList.find('a.action-remove').hide();
                btnSelectFile.attr('disabled', true);
                disabledButton(true);
                sendFiles();
            });

            btnClearFile.on('click', function(e){
                fileUploadList.html('')
                    .append(introUploadText);

                file_uploads = {};
                file_uploaded_count = 0;
                inputFile.val('');
                disabledButton(true);
            });

            $('body').on('click', '#media_list_'+nid+' a.action-select-image', function(e){
                e.preventDefault();
               
                //console.log($(this).attr('data-selected'));
                if($(this).attr('data-selected') == '0'){
                
                    if(e.ctrlKey){
                        selected_files[$(this).data('image-id')] = {
                            uri: $(this).data('image-uri'),
                            file_name: $(this).data('file-name')
                        };
                    }
                    else{
                        if(Object.keys(selected_files).length == 0){
                            selected_files[$(this).data('image-id')] = {
                                uri: $(this).data('image-uri'),
                                file_name: $(this).data('file-name')
                            };
                        }
                        else{
                            for(var key in selected_files){

                                var _id = 'a#action_select_' + key;
                                
                                $(_id).css({
                                    'border-width'  :   1,
                                    'border-style'  :   'solid',
                                    'border-color'  :   '#ccc' 
                                })
                                .attr('data-selected', '0');

                                delete selected_files[key];
                            }

                            selected_files[$(this).data('image-id')] = {
                                uri: $(this).data('image-uri'),
                                file_name: $(this).data('file-name')
                            }; 
                        }
                    }

                    $(this).css({
                        'border-width'  :   1,
                        'border-style'  :   'solid',
                        'border-color'  :   'seagreen' 
                    })
                    .attr('data-selected', '1');

                    //console.log($(this).attr('id'), $(this).attr('data-selected'));

                }
                else{
                    
                    $(this).css({
                        'border-width'  :   1,
                        'border-style'  :   'solid',
                        'border-color'  :   '#ccc' 
                    })
                    .attr('data-selected', '0');
                    
                    //console.log($(this).attr('id'), $(this).attr('data-selected'));

                    delete selected_files[$(this).data('image-id')];

                }

                //console.log(selected_files);

                if(Object.keys(selected_files).length == 0){
                    btnInsertMedia.attr('disabled', true);
                }
                else{
                    btnInsertMedia.attr('disabled', false);
                }

            });

            //Insert From Url Event

            urlInput.on('change keyup paste', function(){
                var _url_input = $(this).val();
                var active_tab = $('ul#vii_tab_'+nid+' li.active a').attr('href');

                selected_url = {}; 

                if(_url_input != ''){
                    urlImagePreview.attr('src', _url_input);
                    urlImagePreview.load(function(){
                        $(this).show();
                        selected_url[1] = {
                            uri: _url_input,
                            file_name: ''
                        };

                        if(active_tab.includes('tab_url')){
                            btnInsertMedia.attr('disabled', false);
                        }
                    })
                    .error(function(){
                        $(this).hide();
                        $(this).attr('src', '');
                        if(active_tab.includes('tab_url')){
                            btnInsertMedia.attr('disabled', true);
                        }
                    });
                }
                else{
                    $(this).val('http://');
                    $(this).select();
                    urlImagePreview.attr('src', '');
                    urlImagePreview.hide();

                    if(active_tab.includes('tab_url')){
                        btnInsertMedia.attr('disabled', true);
                    }
                }
 
                
            }).on('focus', function(){
                $(this).select();
            });

            //Tab Event
            $('#vii_tab_'+nid+' a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href") // activated tab
                if(target.includes('tab_url')){
                    //if(urlInput.val() == '' ||  urlInput.val() == 'http://')
                    if(Object.keys(selected_url).length == 0){
                        btnInsertMedia.attr('disabled', true);
                    }                        
                    else{
                        btnInsertMedia.attr('disabled', false);
                    }                        
                }
                else if(target.includes('tab_media')){
                    if(Object.keys(selected_files).length == 0){
                        btnInsertMedia.attr('disabled', true);
                    }                                            
                    else{
                        btnInsertMedia.attr('disabled', false);
                    }
                        
                }
                else{
                    btnInsertMedia.attr('disabled', true);
                }
            });

            //alert($('ul#vii_tab_'+nid+' li.active a').attr('href'));


            btnInsertMedia.on('click', function(){
                //console.log(selected_images);
                modal.fadeOut();
                var active_tab = $('ul#vii_tab_'+nid+' li.active a').attr('href');
                if(active_tab.includes('tab_media')){
                    
                    plugin.options.insertImage(selected_files, false);
                }
                else if(active_tab.includes('tab_url')){
                    
                    plugin.options.insertImage(selected_url, true);
                }
                
            });
            
            $(document).keyup(function(e){
                if(e.keyCode == 27){
                    modal.fadeOut();
                }
            });
            
            

        };


        /*-----------------PRIVATE FUNCTION------------------*/
        var initUI = function(){

            //Init Upload Manager UI
            buttonUploadBar.append(btnSelectFile).append('&nbsp;')
                .append(btnUploadFile).append('&nbsp;')
                .append(btnClearFile)
                .append(infoUploadText);

            fileUploadList.css({
                'height'    :   plugin.options.height - 70,
                'width'     :   plugin.options.width
            })
            .html('')
            .append(introUploadText);

            uploadManager.html('')
                .append(buttonUploadBar)
                .append(fileUploadList);



            //Init Media Manager UI
            mediaList.css({
                'height'    :   plugin.options.height,
                'width'     :   plugin.options.width
            });
                        
            mediaFileInfo.css({
                'height'    :   plugin.options.height,
                'width'     :   plugin.options.width
            });

            fileManager.html('').append(mediaList);
            
            //Init Insert From Url
            urlInputContainer.css({
                'height'    :   plugin.options.height,
                'width'     :   plugin.options.width
            });

            urlInfoContainer.css({
                'height'    :   plugin.options.height,
                'width'     :   plugin.options.width
            });

            urlInputContainer.append(urlInput)
                .append(urlImagePreview);

            urlManager.html('').append(urlInputContainer);
            
            //Init Tab
            navTab.find('div.media_content').append(fileManager);
            navTab.find('div.media_content_info').append(mediaFileInfo);

            navTab.find('div.upload_content').append(uploadManager);

            navTab.find('div.url_content').append(urlManager);
            navTab.find('div.url_content_info').append(urlInfoContainer);

            //Add slim scroll
            fileUploadList.slimScroll({
                height: fileUploadList.css('height')
            });

            mediaList.slimScroll({
                height: mediaList.css('height')
            });


            //Create Modal
            if(plugin.options.displayMode.toLowerCase() == 'modal'){

                plugin.options.multipleUpload = true;
                
                
                if($element.is('input:file')){
                    
                    //Hide input:file and add the uploader after it
                    inputFile = $element;
                    
                    inputFile.attr({
                            'accept'    :   plugin.options.accept,
                            'multiple'  :   plugin.options.multipleUpload
                    })
                    .after(modal)
                    .after(btnOpenModal)
                    .hide()
                    .on('change', function(e){
                        //files = $.merge(files, e.originalEvent.target.files);
                        mergeUploadFiles(e.originalEvent.target.files);
                        loadUploadFiles();

                    });

                    btnOpenModal.on('click', function(){
                        loadImagesFromServer();
                        modal.fadeIn();
                    });
                }
                else{
                    
                    inputFile.attr({
                            'accept'    :   plugin.options.accept,
                            'multiple'  :   plugin.options.multipleUpload
                    })
                    .hide()
                    .on('change', function(e){
                        //files = $.merge(files, e.originalEvent.target.files);
                        mergeUploadFiles(e.originalEvent.target.files);
                        loadUploadFiles();

                    });;
                    
                    $element.after(modal)
                        .after(inputFile);
                    
                    $element.on('click', function(){
                        loadImagesFromServer();
                        modal.fadeIn();
                    });
                }

                modal.find('div.modal-body').append(navTab);

                modal.find('div.modal-footer').append(btnInsertMedia);  

                modal.find('button.close').on('click', function(){
                    modal.fadeOut();
                });
            }
            else{//inline

            }
            
            
        };
        
        
        
        var checkAcceptedFileType = function(file_type){
            
            var _accept = plugin.options.accept;
            
            if(_accept == "*")
                return true;
            
            var ftype = file_type.split("/");
            
            var type_1 = _accept.split(",");
            
            for(var i = 0; i<type_1.length; i++){
                var t = type_1[i];
                var type_2 = t.split("/");
                if(type_2[1] == "*"){
                    if(ftype[0] == type_2[0]){
                        return true;
                    }
                }
                else{
                    if(file_type == t)
                        return true;
                }
            }
            
            return false;
        }

        var mergeUploadFiles = function(selected){
                        
            var _max = parseInt(plugin.options.maxFiles);
            if(_max > 0){
                if(Object.keys(file_uploads).length >= _max){
                    return false;
                }    

                for(var i=0; i<selected.length; i++){
                    if(Object.keys(file_uploads).length == _max){
                        break;
                    }
                    
                    if(!checkAcceptedFileType(selected[i].type))
                        continue;

                    var maxSize = parseInt(plugin.options.maxFileSize) * 1024 * 1024;
                    if(selected[i].size <= maxSize)
                        file_uploads[++file_uploads_count] = selected[i];
                }
            }
            else if(_max == 0){
                for(var i=0; i<selected.length; i++){
                    
                    if(!checkAcceptedFileType(selected[i].type))
                        continue;
                    
                    var maxSize = parseInt(plugin.options.maxFileSize) * 1024 * 1024;
                    if(selected[i].size <= maxSize)
                        file_uploads[++file_uploads_count] = selected[i];
                }
            }

            //console.log(file_uploads);
        };

        var loadUploadFiles = function(){

            if(Object.keys(file_uploads).length == 0){
                return false;
            }

            fileUploadList.html('');
            disabledButton(false);

            for(var key in file_uploads){

                var f = file_uploads[key];

                var size = (f.size / 1024).toFixed(2);
                if(size < 1024)
                    size = size + ' Kb';
                else
                    size = (size / 1024).toFixed(2) + ' Mb';
                
                var _id = nid + '' + key;

                var item = $([
                    "<div class='col-xs-4 col-md-2 col-sm-2'>",
                    "   <div id='upload_thumbnail_container_" + _id + "' class='thumbnail-container'>",
                    "       <div class='action-container text-right'>",
                    "       </div>",
                    "       <a href='#' class='thumbnail' title='" + f.name + "'>",
                    "           <img id='upload_image_" + _id + "' class='upload-image' src='" + plugin.options.coverUpload + "'/>",
                    "       </a>",
                    "   </div>",
                    "</div>"
                ].join(""));

                var btnRemoveFile = $("<a id='upload_thumbnail_remove_" + _id + "' href='#' data-id='" + key + "' class='action-remove'><span class='color-red'><i class='fa fa-remove'></i></span></a>");

                btnRemoveFile.on('click', function(e){
                    e.preventDefault();
                    $(this).parent().parent().parent().remove();

                    delete files[$(this).data('id')];

                    if((--file_uploads_count) == 0){
                        fileUploadList.html('').append(introUploadText);
                        disabledButton(true);
                        file_uploads = {};
                    }

                });

                item.find('.action-container').first().append(btnRemoveFile);

                fileUploadList.append(item);

            }

            plugin.val('');

        };

        var disabledButton = function(b){
            btnUploadFile.attr('disabled', b);
            btnClearFile.attr('disabled', b);
        };

        var sendFiles = function(){

            file_uploaded_count = Object.keys(file_uploads).length;

            var fileName = plugin.options.fileUploadName;
            if(inputFile.attr('name'))
                fileName = inputFile.attr('name');

            for(var key in file_uploads){
                var formData = new FormData();    
                formData.append(fileName, file_uploads[key]);

                //Append extra data from option.params to formdata
                for(var p in plugin.options.params){
                    formData.append(p, plugin.options.params[p]);
                }
                
                var _id = nid + '' + key;

                uploadFile(formData, '#upload_thumbnail_container_' + _id, '#upload_thumbnail_remove_' + _id, _id);

            }
        };

        var uploadFile = function(form_data, file_id, file_remove_id, key){

            if(plugin.options.csrfMetaName !== false){

                var csrf_name = 'csrf-token';
                if(plugin.options.csrfMetaName != '')
                    csrf_name = plugin.options.csrfMetaName;

                $.ajaxSetup({
                    headers: {
                        //'X-CSRF-TOKEN': $('input[name="_token"]').val()
                        'X-CSRF-TOKEN': $('meta[name="'+csrf_name+'"]').attr('content')
                    }
                });
            }

            var jqXhr = $.ajax({

                url: plugin.options.uploadUrl,
                type: 'POST',
                contentType: false,
                processData: false,
                cache: false,
                data: form_data,

                beforeSend: function(){
                    $(file_remove_id).hide();
                    progressJs(file_id).start();
                },

                xhr: function(){
                    var objXhr = $.ajaxSettings.xhr();
                    if(objXhr.upload){
                        objXhr.upload.addEventListener("progress", function(e){
                            var percent = 0;
                            var pos = e.loaded || e.position;
                            if(e.lengthComputable){
                                percent = Math.ceil((pos / e.total) * 100);
                                progressJs(file_id).set(percent);
                            }
                        }, false);
                    }

                    return objXhr;
                },

                success: function(response){

                    if(file_uploaded_count > 0){
                        //console.log(key + ' UPLOAD DONE! ' + file_uploaded_count);
                        --file_uploaded_count;
                        file_uploads_count = file_uploaded_count;

                        plugin.options.finishUpload(file_uploads[key], key, file_uploaded_count);
                        delete file_uploads[key];

                        setTimeout(function(){
                            $('#upload_thumbnail_container_' + key).parent().remove();
                            var _id = updateFileToMediaList(response);
                            
                            $('#media_list_'+nid).find('img#'+_id).load(function(){
                                $(this).next().hide();
                                $(this).fadeIn();
                            });

                        }, 800);

                        progressJs(file_id).end();
                    }

                    //Update action button when all files were sent to server
                    if(file_uploaded_count == 0){
                        file_uploads_count = 0;
                        btnSelectFile.attr('disabled', false);
                        disabledButton(true);
                        fileUploadList.html('').append(introUploadText);
                        plugin.options.finishAllUpload();
                    }

                },

                error: function(response, status, error){
                    console.log(response.responseText);
                }

            });

        };
        
        var loadImagesFromServer = function(){
            if(plugin.options.getImagesUrl === false)
                return false;
            
            var jqXhr = $.ajax({

                url: plugin.options.getImagesUrl,
                type: 'GET',
                contentType: false,
                cache: false,
                data: [],

                beforeSend: function(){
//                    btnInsertMedia.attr('disabled', true);
                },
                
                success: function(response){
                    
                    console.log(response);
                    mediaList.html('');
                    var image_length = response.length;
                    for(var i=0; i<image_length; i++){
                        updateFileToMediaList(response[i]);    
                    }
                    var count = 0;
                    $('#media_list_'+nid).find('img.lib-image').load(function(){
                        var me = $(this);
                        setTimeout(function(){
                            me.next().hide();
                            me.fadeIn();
//                            count++;
//                            if(count == image_length){
//                                btnInsertMedia.attr('disabled', false);
//                            }
                        }, Math.floor(Math.random() * 800) + 500);
                    });
                    
                    

                },

                error: function(response, status, error){
                    console.log(response.responseText);
                }

            });
        };

        var updateFileToMediaList = function(res){

            var _id = nid + '' + res.id;
            var item = $([
                "<div class='col-xs-4 col-md-2 col-sm-2'>",
                "   <div id='media_thumbnail_container_" + _id + "' class='thumbnail-container'>",
                "       <div class='action-container text-right'>",
                "       </div>",

                "       <a id='action_select_" + _id + "' href='#' class='thumbnail action-select-image' data-image-id='" + _id + "' data-selected='0' data-file-name='" + res.file_name + "' data-image-uri='" + res.uri + "' title='" + res.ori_name + "'>",
                "           <div class='thumbnail-wrapper'>",
                "               <img id='image_" + _id + "' class='lib-image' src='" + res.uri + "' style='display: none;'/>",
                "               <div class='text-center center-position'><i class='fa fa-spinner fa-pulse fa-fw color-seagreen'></i></div>",
                "           </div>",
                "       </a>",

                "   </div>",
                "</div>"
            ].join(""));
            
            mediaList.append(item);

            return 'image_' + _id;
        };

        plugin.init();
    };


    
    $.fn.ViiUploader = function(options){
        
        return $(this).each(function(i){
            
            var _unique_id = '1';
            if(options.uniqueId !== undefined){
                _unique_id = options.uniqueId;
            }
            else{
                _unique_id = $(this).data('unique-id');
            }
            
            if( $(this).data('ViiUploader') === undefined ){
                var plugin = new $.ViiUploader(_unique_id, $(this), options);
                $(this).data('ViiUploader', plugin);
            }

        });
    }
    
}(jQuery));
