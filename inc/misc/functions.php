<?php
function casanova_update_popup(){
global $post;
$post_id = isset( $post->ID ) && $post->ID ? $post->ID : null;
?>

<div class="casanova-popup">

    <div class="casanova-sites-list">
        <h1>Sync Status</h1>
        <ul></ul>
    </div>
    <?php 
    if( $post_id && $post->post_type == 'casino' ){ 
    $casino_sites = Casanova_Casino_Helper::get_casinos_from_list($post_id);
    ?>
    <input type="hidden" name="casino-sites" id="casino-sites" value='<?php echo json_encode($casino_sites, true) ?>'>
    
    <?php 
    if( $casino_sites ){ 
        foreach( $casino_sites as $casino_site ){
        ?>
        <input class="site-ids" data-site="<?php echo $casino_site ?>" type="hidden" id="site-name-<?php echo $casino_site ?>" value="<?php echo get_the_title( $casino_site ) ?>">
    <?php } } ?>
    
    <?php } ?>

</div>
<div class="casanova-popup-overlay casanova-trigger"></div>
<?php
}
add_action( 'admin_footer', 'casanova_update_popup', 10 );

function on_save_list_casino(){

    global $post;

    if( !$post_id = $_POST['post_id'] )
    return false;

    if( !$site = $_POST['site'] )
    return false;

    if( get_field( 'no_sync_global', 'options' ) || get_field( 'no_sync', $post_id ) )
    return false;

    $dt = new DateTime("now", new DateTimeZone('Asia/Dubai'));
    $dt->setTimestamp(time()); 

    if( get_post_type( $post_id ) == 'list' ){
        Casanova_List_Helper::update_lists_rest( $site, $post_id );
        Casanova_List_Helper::update_history( $post_id, $dt->format('Y/m/d H:i:s') );
    }elseif( get_post_type( $post_id ) == 'casino' ){
        Casanova_Casino_Helper::update_casino_rest( $site, $post_id );
        Casanova_Casino_Helper::update_history( $post_id, $dt->format('Y/m/d H:i:s') );
    }
    
    update_field( 'last_sync' , $dt->format('Y/m/d H:i:s'), $post_id);

    wp_send_json_success( ['site' => $site, 'post_id' => $post_id ] );

}
add_action( 'wp_ajax_on_save_list_casino', 'on_save_list_casino' );


/**
 * When a list is saved, lets update the sync key
 *
 * @since 1.0.0
 */
function casanova_update_post(){

    if( !$post_id = $_POST['post_id'] )
    return false;
    
    $postarr = array();
    parse_str($_POST['form_data'], $postarr);

    $postarr['ID'] = $postarr['post_ID'];

    if( $postarr['_acf_changed'] ){
        foreach( $postarr['acf'] as $key => $val ){
            update_field( $key, $val, $postarr['post_ID'] );
        }
    }

    wp_update_post( $postarr );

    wp_send_json_success( ['id' => $post_id, 'post' => $postarr] );

}
add_action( 'wp_ajax_casanova_update_post', 'casanova_update_post' );
