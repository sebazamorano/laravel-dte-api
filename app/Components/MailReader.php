<?php


namespace App\Components;


class MailReader
{
    private $connect_to;
    private $connection;
    private $user;
    private $password;
    private $emails;
    private $unseen;

    public function __construct($connect_to, $user, $password)
    {
        $this->connect_to = $connect_to;
        $this->user = $user;
        $this->password = $password;

        $this->connection = imap_open($connect_to, $user, $password)
        or die("Can't connect to '$connect_to': " . imap_last_error());

        $this->emails = imap_search($this->connection,'ALL');
        $this->unseen = imap_search($this->connection,'UNSEEN');
        rsort($this -> emails);
    }

    public function returnUnseen(){
        return $this->unseen;
    }

    public function email($number)
    {
        $email = imap_fetch_overview($this->connection, $number, 0);
        return $email[0];
    }

    public function message($number)
    {
        $info = imap_fetchstructure($this->connection, $number, 0);

        if($info -> encoding == 3){
            $message = base64_decode(imap_fetchbody($this->connection, $number, 1));
        }
        elseif($info -> encoding == 4){
            $message = imap_qprint(imap_fetchbody($this->connection, $number, 1));
        }
        else
        {
            $message = imap_fetchbody($this->connection, $number, 1);
        }
        return $this->decode_qprint($message);
    }

    public function __destruct() {
        imap_close($this->connection);
    }

    public function __get($var)
    {
        $temp = strtolower($var);
        if (property_exists('mail_reader', $temp))
        {
            return $this->$temp;
        }
        return NULL;
    }

    /*Funcion para decodificar los mensajes*/

    function decode_qprint($str)
    {
        $str = preg_replace("/\=([A-F][A-F0-9])/","%$1",$str);
        $str = urldecode($str);
        $str = utf8_encode($str);
        return $str;
    }

    public function getConnection(){
        return $this->connection;
    }
}
