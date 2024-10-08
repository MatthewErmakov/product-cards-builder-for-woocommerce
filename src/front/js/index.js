jQuery(function($){
    $('a[class*="pcbw_add_to_cart"]').on('click', function(){
        let elem = $(this);
        let viewCartAjaxClass = elem.data('view_cart_classes');

        $(document).on('added_to_cart', function(){
            setTimeout(function () {
                console.log(elem.siblings('a.added_to_cart'));
                elem.siblings('a.added_to_cart').addClass(viewCartAjaxClass);
            }, 500);
        });
    });

    
});