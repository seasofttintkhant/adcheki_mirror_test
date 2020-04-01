<?php

namespace App\Http\Controllers\Api\V1;

use App\Device;
use Illuminate\Http\Request;

use App\Services\AdvanceEmailValidator;

class ContactController extends ApiBaseController
{
    public function check(){
        ini_set("max_execution_time",500);
        $email_validator = new AdvanceEmailValidator();
        $email_validator->setStreamTimeoutWait(20);
        $email_validator->setEmailFrom('seasoft.tint.khant@gmail.com');

        $emails = [
            "chittatthu98@gmail.com",
            "aherkersoemin@gmail.com",
            "khant.a.tint@seasoft.asia",
            "xx@seasoft.asia",
            "aaa@bbb"
        ];

        $result = [];

        $temp_emails = [];
        foreach ($emails as $email){
            if($email_validator->validate($email)){
                $result[$email] = [
                    "valid" => true,
                    "exist" => false
                ];
                $domain =  $email_validator->parse_email($email);
                $temp_emails[$domain][] = $email;
            }else{
                $result[$email] = [
                    "valid" => false,
                    "exist" => false
                ];
            }
        }
        $emails_arr = $temp_emails;

        $domains = array_keys($emails_arr);

        $mx_records_arr = [];
        foreach ($domains as $domain){
            $mx_records = $email_validator->getMXrecords($domain);
            $max_connection_timeout  = 30;
            $timeout = ceil($max_connection_timeout / count($mx_records));
            foreach ($mx_records as $host){
                $steam = @stream_socket_client("tcp://" . $host . ":" . 25, $errno, $errstr, $timeout);
                $email_validator->setSteam($steam);
                if ($steam === FALSE) {
                    return "Problem with the tcp socket";
                } else {
                    stream_set_timeout($steam, $email_validator->stream_timeout);
                    stream_set_blocking($steam, 1);

                    if ($email_validator->_streamCode($email_validator->_streamResponse()) == '220') {
                        $mx_records_arr[$domain][] = $host;
                    } else {
                        fclose($steam);
                        $steam = FALSE;
                    }
                }
            }
        }

        foreach($emails_arr as $domain_1 => $emails){
            $host = "";
            foreach($mx_records_arr as $domain_2 => $mx_records){
                if($domain_1 === $domain_2){
                    if(count($mx_records) > 1){
                        $host = $mx_records[rand(0,count($mx_records)-1)];
                    }else{
                        $host = $mx_records[0];
                    }
                }
            }
            if($host){
                \Log::info($host);
                foreach($emails as $email){
                    $steam = @stream_socket_client("tcp://" . $host . ":" . 25, $errno, $errstr, $timeout);
                    $email_validator->setSteam($steam);
                    if ($steam === FALSE) {
                        return "Problem with the tcp socket";
                    } else {
                        stream_set_timeout($steam, $email_validator->stream_timeout);
                        stream_set_blocking($steam, 1);

                        if ($email_validator->_streamCode($email_validator->_streamResponse()) == '220') {
                            $code = $this->checkEmail($email_validator,$email);
                            $result[$email]["exist"] = $this->checkStatusCode($code);
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function checkEmail($email_validator, $email){
        $email_validator->_streamQuery("HELO " . $email_validator->parse_email($email_validator->from));
        $email_validator->_streamResponse();
        $email_validator->_streamQuery("MAIL FROM: <{$email_validator->from}>");
        $email_validator->_streamResponse();
        $email_validator->_streamQuery("RCPT TO: <{$email}>");
        $code = $email_validator->_streamCode($email_validator->_streamResponse());
        $email_validator->_streamResponse();
        $email_validator->_streamQuery("RSET");
        $email_validator->_streamResponse();
        $code2 = $email_validator->_streamCode($email_validator->_streamResponse());
        $email_validator->_streamQuery("QUIT");
        fclose($email_validator->stream);

        return $code = !empty($code2) ? $code2 : $code;
    }


    public function checkStatusCode($code){
        switch ($code) {
            case '250':
                /**
                 * http://www.ietf.org/rfc/rfc0821.txt
                 * 250 Requested mail action okay, completed
                 * email address was accepted
                 */
            case '450':
            case '451':
            case '452':
                /**
                 * http://www.ietf.org/rfc/rfc0821.txt
                 * 450 Requested action not taken: the remote mail server
                 * does not want to accept mail from your server for
                 * some reason (IP address, blacklisting, etc..)
                 * Thanks Nicht Lieb.
                 * 451 Requested action aborted: local error in processing
                 * 452 Requested action not taken: insufficient system storage
                 * email address was greylisted (or some temporary error occured on the MTA)
                 * i believe that e-mail exists
                 */
                return TRUE;
            case '550':
                return FALSE;
            default:
                return FALSE;
        }
    }

    public function store(Request $request)
    {
        $storedDevice = Device::create([
            'device_id' => $request->device_id
        ]);

        $storedContacts = $storedDevice->contacts()->createMany($request->contacts);

        $emails = $storedContacts->pluck('email')->all();

        $this->validateEmails($emails);

        return $this->jsonResponse(
            201,
            1,
            [],
            [],
            [
                ['email' => 'mgmg@gmail', 'status' => 'true'],
                ['email' => 'kyawkyaw@gmail', 'status' => 'false']
            ]
        );
    }

    public function validateEmails(array $emails = [])
    { }
}
