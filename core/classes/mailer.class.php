<?php

/**
 * Classe util per mail
 */
class Mailer {
	
	/**
	 * Invio mail
	 * @param string $from email mittente
	 * @param string $from nome mittente
	 * @param array/string $address email destinatari.
	 * @param array/string $addressname nomi destinatari.
	 * @param string $subject oggetto della mail
	 * @param string $body corpo dell'email
 	 * @param bool $html formato html della mail
	 * @param string $alt_body corpo alternativo text/plain da usare nelle email in formato HTML
	 * @param array/string $attachments array di strnghe path allegati.
	 */
	function sendMail($from, $fromname, $address, $addressname, $subject, $body, $html = false, $alt_body = null, $attachments = array()){
		
		// Forno array destinatari
		if(!is_array($address)){
			$address=array($address);
		}
		
		if(isset($addressname)) {
			if(!is_array($addressname)){
				$addressname=array($addressname);
			}
		}
		
		// Creo nuovo mailer
		$mail = new PHPMailer();
		$mail->SetLanguage('it');

		// Config
		$mail -> Mailer = _MAILER_TYPE_;
		$mail -> Sendmail = _MAILER_SENDMAIL_PATH_;
		$mail -> Host = _MAILER_SMTP_HOST;
		$mail -> SMTPAuth = _MAILER_SMTP_AUTH;
		$mail -> Username = _MAILER_SMTP_USER;
		$mail -> Password = _MAILER_SMTP_PWD;		
		
		// Formato mail
		$mail -> IsHTML($html);
				
		// Setto from
		$mail->From = $from;
		$mail->FromName = $fromname;
		
		// Oggetto mail
		$mail->Subject = $subject;
		
		// Corpo mail
		$mail->Body = $body;
		
		// Testo alternativo
		if($html) {
			if(!$alt_body) {
				$alt_body = strip_tags($body);
			}
			$mail->AltBody = $alt_body;
			
			if(is_file(_VIEWS_SRC."/core/mail.html")){
				$email=file_get_contents(_VIEWS_SRC."/core/mail.html");
				
				$email=str_replace("{TITLE}",$subject,$email);
				$email=str_replace("{TEXT}",nl2br($body),$email);
				
				$mail->Body = $email;
				
			}
			
		}else{
			$mail->Body = html_entity_decode($body);
		}
		
		// Aggiungo allegati
		if($attachments) {
			foreach ($attachments as $attach) {
       			$mail->AddAttachment($attach);
			}
		}
		
		// Aggiungo destinatari
		foreach($address as $to){
			
			$toname = null;
			if($addressname) {
				$toname = array_shift($addressname);
			}
			
			if($toname) { 
				$mail->AddAddress($to, $toname);	
			} else {
				$mail->AddAddress($to);
			}
		}
		
		// Invio mail
		if($mail->Send()) {
			return true;
		
		} else {
			
			// Traccio errore
			echo $mail -> ErrorInfo;
			return false;
		}
	}
	
}
?>
