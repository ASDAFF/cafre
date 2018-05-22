<?php
    // __autoloades
	CModule::AddAutoloadClasses('mcart.extramail',
	array(
		'PHPMailer' => 'classes/general/class.phpmailer.php',
                'phpmailerException' => 'classes/general/class.phpmailer.php',
                'PHPMailerOAuthGoogle' => 'classes/general/class.phpmaileroauthgoogle.php',
                'POP3' => 'classes/general/class.pop3.php',
                'SMTP' => 'classes/general/class.smtp.php',
				'MCART' => 'classes/general/class.mcart.php',
	)
);
	 
?>