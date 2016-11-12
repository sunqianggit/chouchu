<?php

Class Email_model extends CI_Model {

    public $mail;
    public $bugger;

    function __construct() {
        $this->config->load('email');
        $this->load->library('mailer');

        $this->mailer->PHPMailerAutoload('pop3', true, true);
        $this->mailer->PHPMailerAutoload('smtp', true, true);
        $this->mailer->PHPMailerAutoload('phpmailer', true, true);

        $this->mail = new PHPMailer();

        $this->mail->isSMTP();         
        $this->mail->Host = $this->config->item('email_host');  // Specify main and backup SMTP servers
        $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mail->SMTPDebug = 3;
        $this->mail->Username = $this->config->item('email_user');                 // SMTP username
        $this->mail->Password = $this->config->item('email_password');                           // SMTP password
        $this->mail->SMTPSecure = $this->config->item('email_SMTPSecure');                            // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = $this->config->item('smtp_port');                                    // TCP port to connect to
        
        $this->mail->CharSet="utf-8";
        $this->mail->Encoding = "base64";// TCP port to connect to

        $this->mail->From = $this->config->item('email_from');
        $this->mail->FromName = $this->config->item('email_fromname');
//        $this->mail->WordWrap = 50;
    }

    function send_email($to, $body, $subject = '', $fullname = '', $filename = "", $url = "", $fileType = "") {

        /*
         * FOR PHP V-5.3+
         */
        $url != '' && $binary_content = file_get_contents($url);

        $this->mail->addAddress($to, $fullname);
                                          // Set word wrap to 50 characters
        //$this->mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$this->mail->addAttachment(urlencode('http://www.maths.tcd.ie/~dwilkins/LaTeXPrimer/GSWLaTeX.pdf'), 'new.pdf');    // Optional name

        if ($filename != "") {
            $this->mail->AddStringAttachment($binary_content, $filename, $encoding = 'base64', $type = $fileType);
            $this->mail->isHTML(true);                                  // Set email format to HTML
        }

        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        //$this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $ret = $this->mail->send();
        if(!$ret) {
            $this->bugger = $this->mail->ErrorInfo;
        }
        $this->mail->clearAddresses();
        return $ret;
    }
    
    function print_debugger(){
        return $this->bugger;
    }

}
