<?php
/**
 * User: Andy
 * Date: 28/09/2014
 * Time: 11:04
 */

namespace AVCMS\Core\Email;

use AVCMS\Core\SettingsManager\SettingsManager;
use Swift_Mailer;
use Swift_SmtpTransport;

class Mailer
{
    protected $settings;

    protected $transport;

    protected $mailer;

    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings;

        if ($settings->getSetting('use_smtp')) {
            $encryption = $settings->smtp_encryption;
            if ($encryption == 'none') {
                $encryption = null;
            }

            $this->transport = Swift_SmtpTransport::newInstance($settings->smtp_server, $settings->smtp_port, $encryption);
            $this->transport->setUsername($settings->smtp_username);
            $this->transport->setPassword($settings->smtp_password);
        }
        else {
            $this->transport = \Swift_MailTransport::newInstance();
        }

        $this->mailer = Swift_Mailer::newInstance($this->transport);
    }

    /**
     * @param null $subject
     * @param null $body
     * @param null|string $contentType
     * @param null|string $charset
     * @return \Swift_Message
     */
    public function newEmail($subject = null, $body = null, $contentType = 'text/html', $charset = 'UTF-8')
    {
        if ($charset === null) {
            $charset = 'UTF-8';
        }

        $message = \Swift_Message::newInstance($subject, $body, $contentType, $charset);
        $message->setFrom([$this->settings->getSetting('site_email_address') => $this->settings->getSetting('site_name')]);

        return $message;
    }

    public function send(\Swift_Message $message, &$failedRecipients = null)
    {
        return $this->mailer->send($message, $failedRecipients);
    }
} 
