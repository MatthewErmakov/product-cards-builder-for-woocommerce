import CodeMirror from "./libs/codemirror";

var mixedMode = {
    name: "htmlmixed",
    tags: {
      custom: [[null,/^\[([a-zA-Z0-9_]+)\*?(.*)\]$/ , "wpShortcode"]]
      }
    }

let editor = new CodeMirror.fromTextArea(jQuery('#template-data').get(0), {
    mode: mixedMode,
    lineWrapping: false,
    lineNumbers: true,
    scrollbarStyle: "native", // Use native scrollbars
});

jQuery(function($){
  let timeout;

  function preview() {
    $.ajax({
      url: pccw.ajax_url,
      method: 'POST',
      data: {
        action: 'pccw_preview',
        shortcode: editor.getValue(),
        nonce: pccw.nonce_preview
      },
      
      success: function (response) {
        if ( response.error ) {
          console.error( response.error );
          return;
        }

        if ( response.data.markup ) {
          $('.preview-wrapper .preview-block').html( response.data.markup );
        }

        if ( response.data.styles ) {
          if ( $('#pccw-inline-css').length === 0 ) {
            $('head').append(response.data.styles);
          } else {
            $('#pccw-inline-css').remove();
            $('head').append(response.data.styles);
          }
        }
      }
    });
  }

  preview();

  editor.on('change', function (){
    clearTimeout(timeout);

    timeout = setTimeout(function(){
      preview();
    }, 500);
  });

  $('#save-template').on('click', function(){
    $.ajax({
      url: pccw.ajax_url,
      method: 'POST',
      data: {
        action: 'pccw_save_template',
        shortcode: editor.getValue(),
        nonce: pccw.nonce_save_template
      },

      success: function(response) {
        if ( response.error ) {
          console.log( response.error );
          return;
        }

        if ( response.status === 'Saved' ) {
          console.log( 'Template has been saved' );
        }
      }
    })
  });
});