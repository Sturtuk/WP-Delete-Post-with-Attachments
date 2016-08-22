<?php 
/**
 *  Dated : Aug-22-2016
 *  Author : Edwin F sturt
 * 
 * */
if(!function_exists('WP_Delete_Post_with_Attachments')):
        function WP_Delete_Post_with_Attachments($id){
        		
        		
        		$WP_Posts = get_children(array( 'post_parent' => $id, 'post_type'   => 'any','numberposts' => -1,'post_status' => 'any'));
        		
        		if (is_array($WP_Posts) && count($WP_Posts) > 0):
        			
                    $WP_PATH = wp_upload_dir();
        		 	
        			foreach($WP_Posts as $WP_Post):
        				
        				$_wp_attached_file = get_post_meta($WP_Post->ID, '_wp_attached_file', true);
        				
        				$original = basename($_wp_attached_file);
        				$pos = strpos(strrev($original), '.');
            				if (strpos($original, '.') !== false):
            					$ext = explode('.', strrev($original));
            					$ext = strrev($ext[0]);
            				else:
            					$ext = explode('-', strrev($original));
            					$ext = strrev($ext[0]);
            				endif;
        				
        				$pattern = $WP_PATH['basedir'].'/'.dirname($_wp_attached_file).'/'.basename($original, '.'.$ext).'-[0-9]*x[0-9]*.'.$ext;
        				$original= $WP_PATH['basedir'].'/'.dirname($_wp_attached_file).'/'.basename($original, '.'.$ext).'.'.$ext;
        				if (getimagesize($original)):
        					$thumbs = glob($pattern);
        					if (is_array($thumbs) && count($thumbs) > 0):
        						foreach($thumbs as $thumb):
        							unlink($thumb);
                                endforeach;
        					endif;
        				endif;
        				wp_delete_attachment( $WP_Post->ID, true );
        			endforeach;
        		endif;
        }
add_action('delete_post',		 'WP_Delete_Post_with_Attachments');
add_action('before_delete_post', 'WP_Delete_Post_with_Attachments');    
        
endif;
