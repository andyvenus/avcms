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
    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings;

        $encryption = $settings->smtp_encryption;
        if ($encryption == 'none') {
            $encryption = null;
        }

        $this->transport = Swift_SmtpTransport::newInstance($settings->smtp_server, $settings->smtp_port, $encryption);
        $this->transport->setUsername($settings->smtp_username);
        $this->transport->setPassword($settings->smtp_password);

        $this->mailer = Swift_Mailer::newInstance($this->transport);
    }

    /**
     * @param null $subject
     * @param null $body
     * @param null $contentType
     * @param null $charset
     * @return \Swift_Message
     */
    public function newEmail($subject = null, $body = null, $contentType = null, $charset = null)
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