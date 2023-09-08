<?php

class Casanova_Actions{

    /**
	* Class constructor.
	*
	* @since 2.0.0
	*/
	public function __construct(){
        add_filter( 'admin_body_class', [$this, 'add_user_id_class'] );
	}

	public function add_user_id_class( $classes ){

       $classes .= ' casanova-user-'.get_current_user_id();

        return $classes;
    }
  
}