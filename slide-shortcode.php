<?php 

function stock_slides_shortcode($atts){
    extract( shortcode_atts( array(
        'count'    		  => 3,
        'height'    	  => '730',
        'loop' 			  => 'true',
        'nav' 			  => 'true',
        'autoplay' 		  => 'false',
        'autoplayTimeout' => 5000,
        'dots' 			  => 'true',
    ), $atts) );
     
    $q = new WP_Query(array('posts_per_page' => $count, 'post_type' => 'slide'));      
         
    $list = '

	<script>
		jQuery(window).load(function(){
			jQuery(".stock-slides").owlCarousel({
				items: 1,
				loop: '.$loop.',
				nav: '.$nav.',
				autoplay: '.$autoplay.',
				autoplayTimeout: '.$autoplayTimeout.',
				dots: '.$dots.',
				navText: ["<i class=\'fa fa-angle-left\'></i>", "<i class=\'fa fa-angle-right\'></i>"]
			});
		
                jQuery(".preloader-wraper").fadeOut();

			});
	
	</script>



    <div style="height:'.$height.'px" class="stock-slides-wraper">
    <div class="preloader-wraper">
        <div class="loader">Loading...</div>
    </div>

    <div class="stock-slides">';
    while($q->have_posts()) : $q->the_post();
        $idd = get_the_ID();
        
        if(get_post_meta($idd, 'stock_slide_options', true)){
        	$slide_meta= get_post_meta($idd, 'stock_slide_options', true);
        }else{
        	$slide_meta = array();
        }


        if(array_key_exists('enable_overlay', $slide_meta)){
        	$enable_overlay = $slide_meta['enable_overlay'];
        }else{
        	$enable_overlay = true;
        }

        if(array_key_exists('overlay_percentage', $slide_meta)){
        	$overlay_percentage = $slide_meta['overlay_percentage'];
        }else{
        	$overlay_percentage = .7;
        }

        if(array_key_exists('overlay_color', $slide_meta)){
        	$overlay_color = $slide_meta['overlay_color'];
        }else{
        	$overlay_color = '#181a1f';
        }

        $post_content = get_the_content();
        $list .= '
        <div style="background-image:url('.get_the_post_thumbnail_url($idd, 'large').');height:'.$height.'px" class="stock-slide-item">';

        if($enable_overlay = true){
	 		$list .='
	        <div style="opacity:'.$overlay_percentage.';background-color:'.$overlay_color.'" class="slide-overlay"></div>';
        }else{

        }

        $list .='
            <div class="stock-table">
            	<div class="stock-table-cell">
	            	<div class="container">
	            		<div class="row">
	            			<div class="col-md-6">
								<h1>'.get_the_title($idd).'</h1>
								'.wpautop($post_content).'';


							if(!empty($slide_meta['buttons'])){
								$list.='<div class="stock-slide-buttons">';
									foreach($slide_meta['buttons'] as $button){


									if($button['link_type'] == 1){
										$btn_link = get_page_link($button['link_to_page']);

									}else{
										$btn_link = $button['link_to_external'];
									}


										$list .='<a href="'.$btn_link.'" class="'.$button['type'].'-btn stock-slide-btn">'.$button['text'].'</a>';
									}

								$list.='</div>';
							}

				$list .='				

	            			</div>
	            		</div>
	            	</div>
            	</div>
            </div>
        </div>
        ';        
    endwhile;
    $list.= '</div></div>';
    wp_reset_query();
    return $list;
}
add_shortcode('stock_slides', 'stock_slides_shortcode');  