jQuery( document ).ready( function( $ ) {

    $("body").on('click', ".expandable-section", function(){
        $(this).toggleClass('open');
    });
    
} );
