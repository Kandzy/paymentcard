<?php
/**
 * Created by PhpStorm.
 * User: dkliukin
 * Date: 9/1/18
 * Time: 13:26
 */

namespace App\Controller;

use App\Controllers\Controller;

class ValidationController extends Controller
{
    public function index($request, $response)
    {
        $payment_data = $request->getParams();
        if (isset($payment_data["CardNumber"]) && isset($payment_data['Email']) && isset($payment_data['ExpirationMonth']) && isset($payment_data['ExpirationYear']) && isset($payment_data['CVV2'])) {
            $card = [
                'value' => $payment_data["CardNumber"],
                'status' => $this->validateNumber($payment_data["CardNumber"])
            ];
            $email = [
                'value' => $payment_data['Email'],
                'status' => $this->validateEmail($payment_data['Email']),
            ];
            $expdate = [
                'value' => "{$payment_data['ExpirationMonth']} / {$payment_data['ExpirationYear']}",
                'status' => $this->validateExpiration($payment_data['ExpirationMonth'], $payment_data['ExpirationYear'])
            ];
            $cvv2 = [
                'value' => "{$payment_data['CVV2']}",
                'status' => $this->validateCVV2($payment_data['CVV2'])
            ];
        } else {
            $error = [
                'mail' => $payment_data['Email'],
                'number' => $payment_data["CardNumber"],
                'month' => $payment_data['ExpirationMonth'],
                'year'=> $payment_data['ExpirationYear'],
                'cvv2' => $payment_data['CVV2']
            ];
          return $this->view->render($response, 'validation/crit_error.twig', compact("error"));
        }
        $result = [
            'card' => $card,
            'email' => $email,
            'expdate' => $expdate,
            'cvv2' => $cvv2
        ];
        return $this->view->render($response, 'validation/result.twig', compact("result"));
    }

    private function validateCVV2($cvv2){
        $len = strlen($cvv2);
        $errormsg['error'] = false;
        if ($len < 3 || $len > 3)
        {
            $errormsg = [
                'error' => true,
                'msg' => 'CVV2 length error.'
            ];
        }
        return ($errormsg);
    }

    private function validateExpiration($month, $year){
        $currentYear = intval(date("y"));
        $currentMonth = intval(date("n"));
        $errormsg['error'] = false;
        if (($currentMonth > $month && $currentYear >= $year) || $currentYear + 5 < $year || ($currentYear > $year))
        {
            $errormsg['error'] = true;
            if (($currentMonth > $month && $currentYear >= $year) || ($currentYear > $year))
            {
                $errormsg['msg'] = "This card is expired";
            } else {
                $errormsg['msg'] = "Year of card expiration more then max (5 year)";
            }
        } else {
            return $errormsg;
        }
        return $errormsg;
    }

    private function validateNumber($number) {
        $len = strlen(preg_replace('/[^\d]/','',$number));
        $luhn_status = $this->LuhnAlgo($number);
        if ($len == 16 && $luhn_status) {
            $errormsg['error'] = false;
        } elseif ($len != 16) {
            $errormsg['error'] = true;
            $errormsg['msg'] = "Card number length error.";
        } elseif (!$luhn_status) {
            $errormsg['error'] = true;
            $errormsg['msg'] = "Card is not valid.";
        } else {
            $errormsg['error'] = true;
            $errormsg['msg'] = "Unknown critical error.";
        }
        return $errormsg;
    }

    private function LuhnAlgo($number) {
            $num = strrev(preg_replace('/[^\d]/','',$number));
            $sum = 0;
            for ($i = 0, $j = strlen($num); $i < $j; $i++) {
                if (($i % 2) == 0) {
                    $val = $num[$i];
                } else {
                    $val = $num[$i] * 2;
                    if ($val > 9)  $val -= 9;
                }
                $sum += $val;
            }
            return (($sum % 10) == 0);
    }

    protected function validateEmail($Email){
        $errormsg['error'] = false;
        if(preg_match('#(.+?)\@([a-z0-9-_]+)\.(ua|tv|ru|com|edu|gov|info|net|org|[a-z][a-z])#i', $Email)) {
            return ($errormsg);
        } else {
            $errormsg['error'] = true;
            $errormsg['msg'] = "Invalid email.";
            return ($errormsg);
        }
    }
}