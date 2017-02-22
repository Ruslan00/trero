
$(function(){
    var ul = $('#upload ul');

    $('#drop a').click(function(){
        $(this).parent().find('input').click();
    });

    $('#upload').fileupload({
        dropZone: $('#drop'),
        add: function (e, data) {
            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');
            tpl.find('p').text(data.files[0].name)
                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>');
            data.context = tpl.appendTo(ul);
            tpl.find('input').knob();
            tpl.find('span').click(function(){
                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }
                tpl.fadeOut(function(){
                    tpl.remove();
                });
            });
            var jqXHR = data.submit();
        },

        progress: function(e, data){
            var progress = parseInt(data.loaded / data.total * 100, 10);
            data.context.find('input').val(progress).change();
            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        success: function() {
            location.reload();
        },

        fail:function(e, data){
            data.context.addClass('error');
        }

    });


    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

});

function loadImages() {
    $('#container').hide();
    $('#loader').show();

    $('#container').load('engine.php?mod=images', function() {
        $('#container').show();
        $('#loader').hide();

        var imageLoaded = function() {
            resize();
        }
        $('#container img').each(function() {
            var tmpImg = new Image();
            tmpImg.onload = imageLoaded;
            tmpImg.src = $(this).attr('src');
        });
    });
}

function resize() {

    $('.item').css('width', '');
    $('.item').css('overflow', '');

    var width = parseFloat($('#container').css('width'));
    var str_width = 0;
    var stroka = 1;

    $('.item img').each(function(){

    str_width = parseFloat($(this).css('width'))+str_width;

        $(this).parent().attr('str', stroka);

        if (str_width > width) {
            correct_stroka(stroka, width, str_width);
            str_width = 0;
            stroka++;
        }
    });
}

function correct_stroka(stroka, width, str_width) {

    var count = $('[str=' + stroka + ']').length;
    var minus = parseInt((str_width-width)/count+5);
    var raznica = width-(str_width-parseInt((str_width-width)/count)*count);
    var plus = 0;

    $('[str = ' + stroka + ' ]').each(function(i){

        if (i == 1) plus = raznica; else plus = 0;

        var item_width = parseInt($(this).css('width'))-minus+plus;

        $(this).css('width',  item_width + 'px');
        $(this).css('overflow', 'hidden');
    });
}

$(document).ready(function(){
  $(window).resize();
});

$(window).resize(function(){
    resize();
});