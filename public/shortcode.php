<?php

add_shortcode( 'google_font_checker','google_font_checker_fun' );

//define function to show output
function google_font_checker_fun( $atts, $content = '', $tag ){

	$html = '';
    $html .= '<form action="" class="g-checker-form" method="post" onsubmit="return false">';
    $html .= '<input type="text" name="url" class="form-control url" placeholder="Enter Url...">';
    $html .= '<button class="btn btn-success call_url" type="submit">Go</button> ';
    $html .= '</form>';

	$html .= '<div class="table-responsive done loader">';
	$html .= '</div>';


    return $html;

}



add_action('wp_footer', 'call_custom_ajax');
function call_custom_ajax()
{
?>	

<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
	jQuery('.call_url').on('click',function()
	{

			var url = jQuery('.url').val();

			jQuery('.table-responsive').html('');
			jQuery('.loader').removeClass('done');	


	        jQuery.ajax({
			    type:"POST",
			    url:"<?php echo admin_url('admin-ajax.php'); ?>",
			    data: {action:'my_special_ajax_call_get_url',url:url},
			    dataType: "json",
			    success:function(res)
			    {
				    jQuery('.table-responsive').html('');

			    	var error_msg_dangar = '';
			    	var success_msg = '';

			    	var table_tbody_tr = '';

			    	if (res.data.length != 0) 
			    	{
					    success_msg += '<div class="success_msg">';
				        success_msg += '<p>Google fonts founded!<p>';
				        success_msg += '</div>';

			    		table_tbody_tr +='<table class="table">';
						table_tbody_tr +='<thead><tr><th class="number">NO</th><th class="line">Line</th><th>Code</th></tr></thead>';
						table_tbody_tr +='<tbody class="append_data">';
					    $.each(res.data, function (key, val) {
					    	key++;
					        table_tbody_tr += '<tr>';
					        table_tbody_tr += '<td>'+key+'</td>';
					        table_tbody_tr += '<td>'+val.line+'</td>';
					        table_tbody_tr += '<td>'+val.string+'</td>';
					        table_tbody_tr += '</tr>';
					    });
					    table_tbody_tr +='</tbody>';

				    	jQuery('.table-responsive').html(success_msg);
				    	jQuery('.table-responsive').append(table_tbody_tr);


			    	}
			    	else
			    	{
			    		error_msg_dangar += '<div class="error_msg">';
				        error_msg_dangar += '<p>Google Fonts Not Found!<p>';
				        error_msg_dangar += '</div>';
				    	jQuery('.table-responsive').html(error_msg_dangar);
			    	}


					jQuery('.loader').addClass('done');	

			    }
			});

		});
</script>

<?php
}


/* Get provinces */
add_action('wp_ajax_my_special_ajax_call_get_url', 'get_url');
add_action('wp_ajax_nopriv_my_special_ajax_call_get_url', 'get_url');
function get_url()
{

	
	$data = [];
	$url = '';
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$url = $_POST["url"];
		// $url = 'https://www.besticoder.com/adasdasdasd';
		if(!empty($url)){
			$array = @get_headers($url);
			$st = $array[0];
			if(strpos($st, "200")) {
			    $input = fopen($url, "r");
	 
			    $i = 1;
			    while(!feof($input)) {
			    	$string = fgets($input);
			    	if(strpos($string, 'https://fonts.googleapis.com') !== false){
			    		$myString = htmlspecialchars((string)$string);

			    		preg_match('/href=(["\'])([^\1]*)\1/i', $myString, $link);
			    		if (!empty($link)) {

			    			$string = str_replace("type='text/css'", "", $link[2]); 
			    			$string = str_replace("media='all", "", $string); 
			    			$mystring = rtrim($string, "'"); 
					    	array_push($data, array('line'=> $i, 'string' => $mystring));
			    		}

					}

					$i++;
			    }
			}
		}
	}

	echo json_encode(array('success' => true , 'data' => $data));


    wp_die();

}