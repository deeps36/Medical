<?php
namespace FES\GEET\ClassLib;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
	private $mail;

	public $from_name;
	public $from_email;

	public function __construct() {

		$this->mail = new PHPMailer(true);

		$this->mail->SMTPDebug = 0;                                 // Enable verbose debug output
		$this->mail->isSMTP();                                      // Set mailer to use SMTP
		$this->mail->Host = 'smtp.gmail.com';  						// Specify main and backup SMTP servers
		$this->mail->SMTPAuth = true;                               // Enable SMTP authentication
		$this->mail->Username = 'app.testing.noreply@gmail.com';    // SMTP username
		$this->mail->Password = 'ATN@2017';                         // SMTP password
		$this->mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$this->mail->Port = 465;                                    // TCP port to connect to
		$this->mail->Debugoutput = 'html';
	}
	
	function sendMail($template, $data = array()) {
		
		if(sizeof($data) > 0) {
			$updated = $this->updateTemplate($template, $data); //echo $updated; exit;
		}

		$this->mail->SetFrom($this->from_email, $this->from_name);

		if(isset($data['email']) && !empty($data['email'])) {
			$to_array = explode(",", $data['email']);

			foreach($to_array as $to_email) {
				$this->mail->AddAddress($to_email);
			}
		}
		
		$this->mail->Subject = $data['subject'];
		$this->mail->MsgHTML($updated);

		if($this->mail->Send()) {
			return true;
		} else {
			return false;
		}
	}
	
	function updateTemplate($template, $data) {

            $html = file_get_contents(__DIR__.'/../templates/'.$template.'.html');

            preg_match_all("/{[^}]*}/", $html, $matches);
            if(sizeof($matches) > 0) {
                foreach($matches[0] as $match) {
                    $match = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $match);

                    if(isset($data[$match])) {
                        $html = str_replace("{".$match."}", $data[$match], $html);
                    }

                    if(defined(strtoupper($match))) {
                        $html = str_replace("{".$match."}", constant(strtoupper($match)), $html);
                    }
                }
            }

            return $html;
	}
}