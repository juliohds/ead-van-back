<?php

namespace App\Jobs;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class SendEmail extends Job
{

    protected $email;


    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // set_time_limit(20);
        // ini_set('max_execution_time', 0); //0=NOLIMIT

        $email = $this->email;
        $mail = new PHPMailer;

        // $mail->SMTPDebug = 2;
        $mail->IsSMTP();
        $mail->SMTPAuth  = true;
        $mail->Charset   = 'utf8_decode()';
        $mail->Host  = getenv('MAIL_HOST');
        $mail->Port  = getenv('MAIL_PORT');
        $mail->Username  = getenv('MAIL_USERNAME');
        $mail->Password  = getenv('MAIL_PASSWORD');
        $mail->From  = $email['from'];
        isset($email['attachment']) ? $mail->addAttachment($email['attachment']) : null;
        $mail->FromName  = utf8_decode($email['from']);
        $mail->IsHTML(true);
        $mail->Subject  = utf8_decode($email['subject']);
        $mail->Body  = utf8_decode($email['body']);
        $mail->AddAddress(utf8_decode($email['to']));

        $mail->Send();
        // var_dump($mail->Send(),$mail->ErrorInfo);exit;
    }
}
