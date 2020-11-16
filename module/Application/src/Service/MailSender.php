<?php
namespace Application\Service;

use Laminas\Mail;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Sendmail;

class MailSender 
{
  public function sendMail($sender, $recipient, $subject, $text) 
  {
    $result = false;
    try {
      $mail = new Message();
      $mail->setFrom($sender);
      $mail->addTo($recipient);
      $mail->setSubject($subject);
      $mail->setBody($text);  
      
      $transport = new Sendmail('-f'.$sender);
      $transport->send($mail);
      $result = true;
    } catch(\Exception $e) {
      $result = false;
    }
    
    return $result;
  }
}
