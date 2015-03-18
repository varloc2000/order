jQuery(document).ready(function(){

    $("[tags=tags]").tokenfield();

    add_datetimepicker();

    $("select").addClass("form-control");
    $("li.active").parent().parent().toggleClass("active open", true);
    $('.filtershow').hide();
    $("a.filtershow").click(function(event){
        $(".filterhide").toggle();
        $(".wiki").toggleClass("col-lg-12");
        $(".wiki").toggleClass("col-lg-9");
        $(this).toggle();
    })
    $("a.filterhide").click(function(event){
        $("a.filtershow").toggle();
        $('.filterhide').toggle();
        $(".wiki").toggleClass("col-lg-12");
        $(".wiki").toggleClass("col-lg-9");
    })

    $('.dropdown-toggle#dd').dropdown('toggle');

    $("ul.draggable").sortable({
        connectWith: "ul.droppable",
//        axis: "y",
//        items: "div:not(.no-sortable)",
        remove: function(event, ui) {
//            alert($(this).attr('url'));
            $.ajax({
                url: $(this).attr('url'),
//                url: '/app_dev.php/admin/allby/video/videoalbum/addVideoToAlbum',
                dataType: 'html',
                data:'_sonata_admin=all_by_video.admin.videoalbum&video_id='+ui.item.attr('id')+'&videoalbum_id='+ui.item.attr('album'),
                success: function(){
                    location.reload();
                }
            } );
        }
    });

    $("ul.droppable").sortable({
        connectWith: "ul.draggable",
//        axis: "y",
//        items: "div:not(.no-sortable)",
//        tolerance: 10,
        remove: function(event, ui) {
            $.ajax({
                url: $(this).attr('url'),
                dataType: 'html',
                data:'_sonata_admin=all_by_video.admin.videoalbum&video_id='+ui.item.attr('id')+'&videoalbum_id='+ui.item.attr('album'),
                success: function(){
                    location.reload();
                }
            } );
        }
    });


// Sortable drag and reorder
    $("ul.draggable-ordered").sortable({
        connectWith: "ul.droppable-ordered",
        receive: function(event, ui) {
            $.ajax({
                type: 'POST',
                url: $(this).data('url'),
                dataType: 'json',
                data: '_sonata_admin='
                + $(this).data('admin')
                + '&id='
                + ui.item.attr('id')
                + '&container_id='
                + ui.item.data('container'),
                success: function(){
                    location.reload();
                }
            });
        }
    });

    $("ul.droppable-ordered").sortable({
        connectWith: "ul.draggable-ordered",
        update: function(event, ui) {
            var idx = [];

            console.log($(this));
            $(this).find('li').each(function() {
                idx.push($(this).attr('id'));
            });

            console.log(idx);

            $.ajax({
                type: 'POST',
                url: $(this).data('url'),
                dataType: 'json',
                data: {
                    _sonata_admin: $(this).data('admin'),
                    container_id: ui.item.data('container'),
                    idx: idx
                },
                success: function(){
                    location.reload();
                }
            });
        },
        receive: function(event, ui) {
            var idx = [];

            console.log($(this));
            $(this).find('li').each(function() {
                idx.push($(this).attr('id'));
            });

            console.log(idx);

            $.ajax({
                type: 'POST',
                url: $(this).data('url'),
                dataType: 'json',
                data: {
                    _sonata_admin: $(this).data('admin'),
                    container_id: ui.item.data('container'),
                    idx: idx
                },
                success: function(){
                    location.reload();
                }
            });
        }
    });
// End Sortable drag and reorder

    $('.side-nav').css('height', ($(window).height()-50)+'px');

    $('[trim=trim]').blur(function( e ){
        var $this = $(this);
        $this.val($this.val().replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' '));
    });
});

var add_datetimepicker = function() {

    $.fn.datetimepicker.dates['ru'] = {
        days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"],
        daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб", "Вск"],
        daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
        months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
        monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
        today: "Сегодня",
        suffix: [],
        meridiem: []
    };

    $(".dp").datetimepicker({
        language:  'ru',
        autoclose: true,
        todayBtn: true,
        format: 'dd.mm.yyyy hh:ii:ss',
        pickerPosition: 'bottom-left'
    }).on('changeDate', function(e){
            if ($(this).find('input[name*=start]').length)
            {
                $(this).parent().parent().find("div.dp input[name*=end]").parent().datetimepicker("setStartDate", e.date );
                if ( !$(this).parent().parent().find("div.dp input[name*=end]").val() )
                {
                    var now = new Date( e.date );
                    $(this).parent().parent().find("div.dp input[name*=end]").val( now.format("dd.mm.yyyy HH:MM") )
                    $(this).parent().parent().find("div.dp input[name*=end]").parent().datetimepicker("update");
                    $(this).parent().parent().find("div.dp input[name*=start]").parent().datetimepicker("setEndDate", now );
                }
            }
            if ($(this).find('input[name*=end]').length)
            {
                $(this).parent().parent().find("div.dp input[name*=start]").parent().datetimepicker("setEndDate", e.date );
                if ( !$(this).parent().parent().find("div.dp input[name*=start]").val() )
                {
                    var now = new Date( e.date );
                    $(this).parent().parent().find("div.dp input[name*=start]").val( now.format("dd.mm.yyyy HH:MM") )
                    $(this).parent().parent().find("div.dp input[name*=start]").parent().datetimepicker("update");
                    $(this).parent().parent().find("div.dp input[name*=end]").parent().datetimepicker("setStartDate", now );
                }
            }

        });

};

$(window).resize(function(){
    $('.side-nav').css('height', ($(window).height()-50)+'px');
});

var AllBy = {
    $container: $('#wrapper')
};

/**
 * @param {string} filesSelector
 * @param {string} formId
 * @param {string} url
 *
 * @constructor
 */
AllBy.MediaCollectionUploader = function (filesSelector, formId, url) {
    this.url            = url;
    this.$form          = $('#' + formId);
    this.fileSelector   = filesSelector;
    this.$filesFields   = $(filesSelector);
};
AllBy.MediaCollectionUploader.prototype.init = function () {
    var _self = this,
        initializeFunction = function() {_self._initializeFileupload($(this));};

    this.$filesFields.each(initializeFunction);

    $('body').on('click', this.fileSelector, initializeFunction);
};
AllBy.MediaCollectionUploader.prototype._initializeFileupload = function ($initiator) {
    var _self = this,
        uploaderView = this.getUploaderViewByInitiator($initiator),
        options = $.extend(
            {},
            AllBy.MediaCollectionUploader.defaultFileuploadOptions,
            {
                url: _self.url,
                start: function (e, data) {
                    console.log('Start upload!');

                    uploaderView.field.prop('disabled', 'disabled');
                    uploaderView.deleteUploadedFileLink.hide();
                    uploaderView.sizeContainer.hide();
                    uploaderView.errorsContainer.html('');
                    uploaderView.progressBar.css(
                        'width',
                        '0px'
                    );
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    uploaderView.progressBar.css(
                        'width',
                        progress + '%'
                    );
                },
                done: function (e, data) {
                    if (undefined !== data.result.file) {

                        var file = data.result.file;

                        if (undefined !== file.errors) {
                            uploaderView.errorsContainer.append(file.errors);
                            uploaderView.field.prop('disabled', !$.support.fileInput);
                            uploaderView.deleteUploadedFileLink.show();
                            uploaderView.progressBar.css(
                                'width',
                                '0px'
                            );

                            return false;
                        }

                        uploaderView.sizeContainer.show();
                        uploaderView.uploadedField.val(file.id); // Set uploaded attachment id into hidden field

                        try {
                            // Delete uploaded file link
                            uploaderView.deleteUploadedFileLink.attr(
                                'href',
                                uploaderView.deleteUploadedFileLink.data('href').replace(/__id__/g, file.id)
                            ).show();
                        } catch(err) {
                            console.log(err.message);
                        }

                        try {
                            // Show uploaded file link
                            uploaderView.showUploadedFileLink.attr(
                                'href',
                                uploaderView.showUploadedFileLink.data('href').replace(/__id__/g, file.id)
                            ).show();
                        } catch(err) {
                            console.log(err.message);
                        }
                    } else {
                        uploaderView.errorsContainer.append('Upload failed. File type not allowed!');
                    }

                    uploaderView.progressBar.css(
                        'width',
                        '0px'
                    );

                    uploaderView.field.prop('disabled', !$.support.fileInput);
                }
            }
        );

    $initiator.fileupload(options).prop('disabled', !$.support.fileInput);
};
AllBy.MediaCollectionUploader.prototype.getUploaderViewByInitiator = function ($initiator) {
    var fieldId         = $initiator.attr('id'),
        fieldName       = $initiator.data('name'),
        uploadedFieldId = fieldId.replace(new RegExp('_' + fieldName + '$'), '_id');

    // Return object prepared to work with file uploader callbacks
    return {
        field: $initiator,
        fieldId: fieldId,
        fieldName: fieldName,
        errorsContainer: $('#errors-' + fieldId),
        deleteUploadedFileLink: $('#uploaded-' + fieldId + ' .delete-link'),
        showUploadedFileLink: $('#uploaded-' + fieldId + ' .show-link'),
        progressBar: $('#progress-' + fieldId + ' .progress-bar'),
        sizeContainer: $('#progress-' + fieldId + ' .uploaded-size'),
        uploadedField: $('#' + uploadedFieldId)
    };
};
AllBy.MediaCollectionUploader.defaultFileuploadOptions = {
    loadImageMaxFileSize: 600000000, // 600 MB
    progressInterval: 300,
    replaceFileInput: false,
    maxNumberOfFiles: 1,
    dataType: 'json',
    fail: function (e, data) {
        console.log('Ajax File upload error :' + data.errorThrown);
    }
};