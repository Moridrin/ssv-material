<?php
function mp_ssv_filter_content($content) {
	$content = str_replace('<button>', '<button class="mui-btn mui-btn--primary">', $content);
	$content = str_replace('<input type="submit"', '<input type="submit" class="mui-btn mui-btn--primary"', $content);
	$content = str_replace('<input name="submit"', '<input name="submit" class="mui-btn mui-btn--primary"', $content);
	if (strpos($content, '[mp_ssv_test]') !== false) {
		mp_ssv_test_content();
		//$content = str_replace('[mp_ssv_test]', , $content);
	}
	return $content;
}

function mp_ssv_replace_tag($content, $tag, $url) {
	$final_content = apply_filters('the_content', explode($tag, $content)[0]);
	ob_start();
	include_once get_stylesheet_directory().$url;
	$final_content .= ob_get_contents();
	ob_end_clean();
	$final_content .= apply_filters('the_content', explode($tag, $content)[1]);
	return $final_content;
}

function mp_ssv_test_content() {
	$to = "j.berkvens@allterrain.nl";
	$subject = "test_email";
	$message = "this is a test email.";
	$returner = wp_mail($to, $subject, $message);
	$returner .= "Test Mail sent";
	return $returner;
}
?>