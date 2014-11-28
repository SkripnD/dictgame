<?php

use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

/**
 * Class for sending an email
 * @see http://framework.zend.com/manual/2.2/en/modules/zend.mail.introduction.html
 */
class Mailer
{
    protected static $transport;
    
    public $mail;
    
    public function __construct()
    {
        $this->mail = new Mail\Message();
    }
    
    /**
     * Send Mail\Message
     * @return bool
     */
    public function send()
    {
        if (null === self::$transport) {
            $settings = Yii::app()->params->mail;
            
            /** @see config/app **/
            if ($settings['smtp'] == true) {
                $transport = new SmtpTransport(new SmtpOptions($settings['options']));
            } else {
                $transport = new Mail\Transport\Sendmail();
            }
            self::$transport = $transport;
        }
        
        $this->prepareMessage();

        $self = $this;
        return Yii::app()->controller->tryMe(function () use ($self) {
            $self::$transport->send($self->mail);
            return true;
        }, function () {
            return false;
        });
    }
    
    private function prepareMessage()
    {
        if (strlen(settings()->emailPrefix)) {
            $this->mail->setSubject(settings()->emailPrefix.': '.$this->mail->getSubject());
        }
    }
}
