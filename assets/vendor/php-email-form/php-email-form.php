<?php
class PHP_Email_Form {
    public $to = '';
    public $from_name = '';
    public $from_email = '';
    public $subject = '';
    public $smtp = [];
    private $messages = [];

    public function add_message($message, $label = '', $order = 0) {
        $this->messages[] = [$message, $label, $order];
    }

    public function send() {
        if (!empty($this->smtp)) {
            return $this->send_smtp();
        } else {
            return $this->send_mail();
        }
    }

    private function send_mail() {
        $headers = "From: " . $this->from_name . " <" . $this->from_email . ">\r\n";
        $headers .= "Reply-To: " . $this->from_email . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $message = $this->build_message();

        return mail($this->to, $this->subject, $message, $headers);
    }

    private function send_smtp() {
        $host = $this->smtp['host'];
        $username = $this->smtp['username'];
        $password = $this->smtp['password'];
        $port = $this->smtp['port'];
        $encryption = isset($this->smtp['encryption']) ? $this->smtp['encryption'] : 'tls';

        $transport = (new Swift_SmtpTransport($host, $port, $encryption))
            ->setUsername($username)
            ->setPassword($password);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($this->subject))
            ->setFrom([$this->from_email => $this->from_name])
            ->setTo([$this->to])
            ->setBody($this->build_message(), 'text/html');

        return $mailer->send($message);
    }

    private function build_message() {
        $output = "";
        foreach ($this->messages as $message) {
            $output .= !empty($message[1]) ? $message[1] . ": " . $message[0] . "<br>" : $message[0] . "<br>";
        }
        return $output;
    }
}
?>
