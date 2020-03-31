<?php

namespace App\Http\Controllers\Api\V1;

use App\Device;
use Illuminate\Http\Request;

use App\Services\AdvanceEmailValidator;

class ContactController extends ApiBaseController
{
    public function check(){
        $email = "aherkersoemin@gmail.com";
        $gg = new AdvanceEmailValidator();
        $gg->setStreamTimeoutWait(20);
        $gg->setEmailFrom('seasoft.tint.khant@gmail.com');
        if(!$gg->validate($email)){
            return "wrong email format";
        }
        $mxs = $gg->getMXrecords($gg->parse_email($email));
        $max_connection_timeout  = 30;
        $timeout = ceil($max_connection_timeout / count($mxs));
        foreach ($mxs as $host){
            $steam = @stream_socket_client("tcp://" . $host . ":" . 25, $errno, $errstr, $timeout);
            $gg->setSteam($steam);
            if ($steam === FALSE) {
                return "Problem with the tcp socket";
            } else {
                stream_set_timeout($steam, $gg->stream_timeout);
                stream_set_blocking($steam, 1);

                if ($gg->_streamCode($gg->_streamResponse()) == '220') {
//                    $gg->edebug("Connection success {$host}");
                    break;
                } else {
                    fclose($steam);
                    $steam = FALSE;
                }
            }
        }

        $gg->_streamQuery("HELO " . $gg->parse_email($gg->from));
        $gg->_streamResponse();
        $gg->_streamQuery("MAIL FROM: <{$gg->from}>");
        $gg->_streamResponse();
        $gg->_streamQuery("RCPT TO: <{$email}>");
        $code = $gg->_streamCode($gg->_streamResponse());
        $gg->_streamResponse();
        $gg->_streamQuery("RSET");
        $gg->_streamResponse();
        $code2 = $gg->_streamCode($gg->_streamResponse());
        $gg->_streamQuery("QUIT");
        fclose($gg->stream);

        $code = !empty($code2) ? $code2 : $code;

        var_dump($this->checkStatusCode($code));

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

        $storedDevice->contacts()->createMany($request->contacts);

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
}
