jQuery( document ).ready( function( $ ) {

    let flag = false;

    $( '#post' ).on( 'submit', function(e) {

        let $form = $(this),
        completedSites = 0,
        isCasino = $("#casino-sites").length;
        
        let connectedSites = isCasino ? JSON.parse($("#casino-sites").val()) : $("#acf-field_632c65da476cc").val();

        if( connectedSites.length == 0 )
        return true;

        if( !flag )
        e.preventDefault();

        if( flag )
        return true;

        $("body").addClass('casanova-submit');
        $(".casanova-sites-list ul").prepend('<li class="post-update loading">Updating Post <span>In Progress...</span></li>');

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'casanova_update_post',
                form_data: $form.serialize(),
                post_id: acf.data.post_id,
            }
        }).done(function(response){

            $(".casanova-sites-list ul .post-update").removeClass('loading').addClass('success');
            $(".casanova-sites-list ul .post-update").find('span').text('Complete');

            connectedSites.forEach( (element, index) => {

                if( !isCasino ){
                    let siteName = $("#acf-field_632c65da476cc").next().find('li').eq(index).text().replace('×', '');
                    $(".casanova-sites-list ul").append( `<li class="loading" data-site="${element}">${siteName} <span>Syncing...</span> </li>` );
                }else if( isCasino ){
                    let siteName = $(`#site-name-${element}`).val();
                    $(".casanova-sites-list ul").append( `<li class="loading" data-site="${element}">${siteName} <span>Syncing...</span> </li>` );
                }

                setTimeout( function(){
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                            action: 'on_save_list_casino',
                            post_id: acf.data.post_id,
                            site: element,
                        }
                    }).done(function(response){

                        if( completedSites, completedSites == (connectedSites.length - 1) ){
                            flag = true;
                            location.reload();
                        }

                        let siteElement = $(".casanova-sites-list").find("[data-site='" + response.data.site + "']");
                        siteElement.removeClass('loading').addClass('success');
                        siteElement.find('span').text('Complete');
        
                        completedSites++;

                    });
                }, index * 1000);

            });

        });

    });
    
} );
