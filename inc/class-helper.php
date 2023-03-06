<?php
/**
 * Class responsible for handlind post type meta
 * 
 * @since 1.0.0
 *
 */
class Casanova_Helper{

    /**
     * Get acf meta.
     *
     * @since 1.0.0
     */
    public static function get_meta( $id, $meta_keys = [] ){

        if( empty($meta_keys) )
        return false;

        // In case the key was fetched as a string instead of array
        if( !is_array($meta_keys) && !empty($meta_keys) )
        $meta_keys = [$meta_keys];

        $meta_arr = [];

        foreach( $meta_keys as $key => $val )
        $meta_arr[$val] = get_field( $val, $id );

        return $meta_arr;

    }

    /**
     * Get taxonomy terms.
     * 
     * @param int @post_id - The post id to get terms for
     * @param string @tax - The taxonomy in which we want to get terms for
     *
     * @since 1.0.0
     */
    public static function get_tax_terms( $post_id, $tax ){
        if($post_id){
            $terms = get_the_terms( $post_id, $tax );
        }else{
            $terms = get_terms( $tax );
        }

        return $terms && !is_wp_error( $terms ) ? $terms : false;
    }

    /**
     * Returns the first term of a given tax for a post.
     *
     * @since 1.0.0
     */
    public static function get_first_tax_term( $post_id, $tax ){
        $terms = self::get_tax_terms($post_id, $tax);
        return $terms && isset($terms[0]) ? $terms[0] : false;
    }

}