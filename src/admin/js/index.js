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
    let productSelectPreviewVal = $('select[name="pccw_preview_product"]').val();

    productSelectPreviewVal = productSelectPreviewVal === 'Choose a product' ? false : productSelectPreviewVal;

    $.ajax({
      url: pccw.ajax_url,
      method: 'POST',
      data: {
        action: 'pccw_preview',
        shortcode: editor.getValue(),
        product_id: productSelectPreviewVal,
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

    $('#save-template').removeAttr('disabled');

    timeout = setTimeout(function(){
      preview();
    }, 500);
  });

  $('select[name="pccw_preview_product"]').on('change', function (){
    clearTimeout(timeout);

    $('#save-template').removeAttr('disabled');

    timeout = setTimeout(function(){
      preview();
    }, 500);
  });

  $('#save-template').on('click', function(){
    let productSelectPreviewVal = $('select[name="pccw_preview_product"]').val();

    productSelectPreviewVal = productSelectPreviewVal === 'Choose a product' ? false : productSelectPreviewVal;

    $('#save-template').attr('disabled', '');

    $.ajax({
      url: pccw.ajax_url,
      method: 'POST',
      data: {
        action: 'pccw_save_template',
        shortcode: editor.getValue(),
        product_id: productSelectPreviewVal,
        nonce: pccw.nonce_save_template
      },

      success: function(response) {
        if( $('.product-cards-customiser-inner').find('.popup').length === 0 ) {
          $('.product-cards-customiser-inner h1').after(`<div style="display: none" class="popup${response.data.status === 'saved' ? ' updated' : ' error'}">${response.data.message}</div>`);
          $('.product-cards-customiser-inner .popup').slideDown();
        }

        setTimeout(function(){
          $('.product-cards-customiser-inner').find('.popup').slideUp();

          setTimeout( function () {
            $('.product-cards-customiser-inner').find('.popup').remove();
          }, 500 );
        }, 2000);
      }
    })
  });

  $(document).on('click', '.preview-block *', function (e) {
    e.preventDefault();
  });
});