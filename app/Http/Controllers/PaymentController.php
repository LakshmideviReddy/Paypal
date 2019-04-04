<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;

class PaymentController extends Controller
{
    private $_api_context;
    public function __construct(){
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret']
        ));
        $this->_api_context->setConfig( $paypal_conf['settings']);
    }

    public function payWithpaypal(Request $request)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName('Item 1')
            ->setCurrency('USD')
            ->setQuantity(1)
            //->setSku("123123") // Similar to `item_number` in Classic API
            ->setPrice($request->get('amount'));

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($request->get('amount'));
           // ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');
          //  ->setInvoiceNumber(uniqid());

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('status'))
            ->setCancelUrl(URL::to('status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try{
            $payment->create($this->_api_context);
        }
        catch(\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')){
                \Session::put('error', 'Connection timeout');
                return Redirect::to('/');
            }else{
                \Session::put('error', 'Some error occur, sorry for inconvienient');
                return Redirect::to('/');
            }
        }

        foreach ($payment->getLinks() as $link){
            if ($link->getRel() == 'approval_url'){

                $redirect_url = $link->getHref();
                break;
            }
        }

        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)){
            return Redirect::away($redirect_url);
        }
        \Session::put('error', 'Unknown error occured');
        return Redirect::to('/');
    }

    public function getPaymentStatus(Request $request){
        $payment_id = Session::get('paypal_payment_id');
        Session::forget('paypal_payment_id');

        if(empty(Input::get('PayerID')) || empty(Input::get('token'))){
            \Session::put('error','Payment failed');
            return Redirect::to('/');
        }

        //$paymentId = $_GET['paymentId'];
        $payment = Payment::get($paymentId, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        $result = $payment->execute($execution, $this->_api_context);

        if($result->getState() == 'approved') {
            \Session::put('success','Payment success');
            return Redirect::to('/');
        }

        \Session::put('error','Payment failed');
        return Redirect::to('/');
    }

}
