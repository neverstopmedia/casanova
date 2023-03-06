jQuery( document ).ready( function( $ ) {

    $("body").on('click', ".expandable-section", function(){
        $(this).toggleClass('open');
    });

    $("#demo-site-select").on('click', function(){
        $(".demo-preview iframe").attr('src', $(this).val());
    })
    
} );
