<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">
<?php 
	$CI =& get_instance();
	$CI->load->library('email');
	$CI->email->set_mailtype('html');
	$CI->email->from(EMAIL_APP, HELLOW);
	$CI->email->to(EMAIL_APP_NOTIFICACION);
	$CI->email->subject("ERROR");
	
	
	$errores_["line"]		= $line;
	$errores_["filepath"]	= $filepath;
	$errores_["severity"]	= $severity;
	$errores_["message"]	= $message;
	$CI->email->message($CI->load->view('core_template/email_errores',$errores_,true)); 
	//$CI->email->send();		
?> 
		
<?php log_message("ERROR",print_r($severity,true)); ?>
<?php log_message("ERROR",print_r($message,true)); ?>
<?php log_message("ERROR",print_r($filepath,true)); ?>
<?php log_message("ERROR",print_r($line,true)); ?>

<h4>A PHP Error was encountered</h4>

<p>Severity: <?php echo $severity; ?></p>
<p>Message:  <?php echo $message; ?></p>
<p>Filename: <?php echo $filepath; ?></p>
<p>Line Number: <?php echo $line; ?></p>

</div> 