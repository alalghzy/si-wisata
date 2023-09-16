jQuery( document ).ready(function($) {
    'use strict';
    
    wp.Travelcustomizer = {
        init: function() {
            $(window).load(function(){
                $('.customize-control-textarea .wp-editor-wrap .wp-editor-container > textarea').each(function(){
                    var tArea = $(this),
                        id = tArea.attr('id'),
                        editor = tinyMCE.get(id),
                        setChange,
                        content;

                    if(editor){
                        editor.onChange.add(function (ed, e) {
                            ed.save();
                            content = editor.getContent();
                            clearTimeout(setChange);
                            setChange = setTimeout(function(){
                                tArea.val(content).trigger('change');
                            },500);
                        });
                    }

                    tArea.css({
                        visibility: 'visible'
                    }).on('keyup', function(){
                        content = tArea.val();
                        clearTimeout(setChange);
                        setChange = setTimeout(function(){
                            content.trigger('change');
                        },500);
                    });
                });
            });
        }
    };
        
    
    tinyMCE.init({
        oninit : wp.Travelcustomizer.init()
    });

});