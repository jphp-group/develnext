<?php
namespace script;

use php\concurrent\TimeoutException;
use php\gui\framework\AbstractScript;
use php\io\IOException;
use php\io\Stream;
use php\jsoup\Connection;
use php\jsoup\ConnectionResponse;
use php\jsoup\Document;
use php\jsoup\Jsoup;
use php\lang\IllegalArgumentException;
use php\lang\Thread;
use php\lib\arr;
use php\lib\str;
use php\mail\Email;
use php\mail\EmailBackend;
use php\util\Scanner;

class MailScript extends AbstractScript
{
    /**
     * @var string
     */
    public $hostName;

    /**
     * @var int
     */
    public $smtpPort;

    /**
     * @var bool
     */
    public $sslOnConnect;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var int
     */
    public $timeout = 15000;

    /**
     * @var string
     */
    public $mailCharset = 'UTF-8';

    /**
     * @var string
     */
    public $mailFrom = '';

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
    }

    protected function fetchBackend()
    {
        $backend = new EmailBackend();
        $backend->hostName = $this->hostName;
        $backend->smtpPort = $this->smtpPort;
        $backend->sslOnConnect = $this->sslOnConnect;

        $backend->socketConnectionTimeout = $this->timeout;
        $backend->socketTimeout = $this->timeout;

        if ($this->login) {
            $backend->setAuthentication($this->login, $this->password);
        }

        return $backend;
    }

    protected function fetchMail($email)
    {
        if (is_array($email)) {
            $r = new Email();

            foreach (['charset', 'from', 'message', 'htmlMessage', 'textMessage', 'subject', 'headers'] as $prop) {
                if (isset($email[$prop])) {
                    $r->{"set$prop"}($email[$prop]);
                }
            }

            foreach (['bcc', 'cc', 'to'] as $prop) {
                if (isset($email[$prop])) {
                    $value = $email[$prop];

                    if (!is_array($value)) {
                        $value = [$value];
                    }

                    $r->{"set$prop"}($value);
                }
            }

            if (is_array($email['from'])) {
                $r->setFrom(arr::first($email['from']), $email['name'] ?: $email[1], ($email['charset'] ?: $email[2]) ?: 'UTF-8');
            }

            if (!$email['from']) {
                $r->setFrom($this->mailFrom);
            }

            if (!$email['charset']) {
                $r->setCharset($this->mailCharset);
            }

            return $r;
        } else {
            throw new IllegalArgumentException("Passed email argument is nod valid, it must be array of Email instance");
        }
    }


    /**
     * @param Email|array $email
     * @return null
     */
    public function send($email)
    {
        if ($this->disabled) {
            return null;
        }

        $email = $this->fetchMail($email);

        try {
            uiLater(function () use ($email) {
                $this->trigger('sending', ['email' => $email]);
            });

            $result = $email->send($this->fetchBackend());

            uiLater(function () use ($email) {
                $this->trigger('send', ['email' => $email]);
            });
            return $result;
        } catch (\Exception $e) {

            uiLater(function () use ($e, $email) {
                $this->trigger('error', ['mail' => $email, 'error' => $e]);
            });

            return null;
        }
    }

    /**
     * @param $email
     * @param callable|null $callback
     */
    public function sendAsync($email, callable $callback = null)
    {
        (new Thread(function () use ($email, $callback) {
            if ($this->send($email)) {
                if ($callback) uiLater($callback);
            }
        }))->start();
    }
}