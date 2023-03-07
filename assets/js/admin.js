jQuery( document ).ready( function( $ ) {

    $("body").on('click', ".expandable-section", function(){
        $(this).toggleClass('open');
    });

    $("#demo-site-select").on('change', function(){
        $(".demo-preview iframe").attr('src', $(this).val());
    });
    
} );
