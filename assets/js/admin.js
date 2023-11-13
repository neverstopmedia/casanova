jQuery( document ).ready( function( $ ) {

    $("body").on('click', ".expandable-section", function(){
        $(this).toggleClass('open');
    });

    $("#demo-site-select").on('change', function(){
        $(".demo-preview iframe").attr('src', $(this).val());
    });

    // Let's update the casino application domains
    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'get_casinos_with_apps',
        }
    }).done(function(response){

        console.log(response);

        if( response.success == true ){

            let casinos = response.data.casinos;

            casinos.forEach( (casino, index) => {

                // Let's update each casino on its own
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'get_casino_apps',
                        casino_id: casino.ID
                    }
                }).done(function(response){

                    // Let's start by checking domains for every casino
                    if( response.success == true ){

                        let domainList = response.data.domain_list;

                        for( let index = 0; index < domainList.length; index++ ){

                            let domain = domainList[index];

                            setTimeout( function(){

                                $.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: {
                                        action: 'check_and_update_domain',
                                        domain: domain,
                                        casino_id: casino.ID
                                    }
                                }).done(function(response){

                                    console.log(response);

                                    // If response.continue is false, this means we found the domain that is not filtered, and we
                                    // can now exit the loop and the function entirely.
                                    if( response.success == true && response.data.continue == false ){

                                        console.log('done');

                                    }

                                });

                            }, index * 15000);

                        }

                    }

                });
                
            });

        }

    });
    
} );
