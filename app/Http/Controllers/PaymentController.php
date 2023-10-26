<?php

namespace App\Http\Controllers;

use Logger;
use Exception;
use Carbon\Carbon;
use Omnipay\Omnipay;
use App\Models\Course;
use App\Models\CourseTaken;
use App\Models\Instructor;
use App\Models\Payout;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class PaymentController extends Controller
{

    public function payout_detail(Request $request, $batch_id)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
    }

    private function payout($email, $price)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();


        // $batch_id = config('payment.batch_id') + 1;
        $batch_id = Carbon::now()->toDateTimeString();
        // config([
        //     'payment.batch_id' => $batch_id,
        // ]);

        $data = [
            "items" => [
                [
                    "receiver" => $email,
                    "amount" => [
                        "currency" => "USD",
                        "value" => $price
                    ],
                    "recipient_type" => "EMAIL",
                    "note" => "Thanks for your patronage!",
                    // "sender_item_id" => "201403140001",
                    // "recipient_wallet" => "RECIPIENT_SELECTED"
                ]
            ],
            "sender_batch_header" => [
                "sender_batch_id" => $batch_id,
                "email_subject" => "You have a payout!",
                "email_message" => "You have received a payout! Thanks for using our service!"
            ]
        ];

        $response = $provider->createBatchPayout($data);

        return $response;

        // dd($response);
    }

    // function pp()
    // {
    //     $res = $this->payout('sb-wyn8n26007430@personal.example.com', '10');
    //     dd($res);
    // }


    public function checkout(Request $request)
    {
        $course_id = request()->input('course_id');
        $course = Course::find($course_id);
        // Logger($course);


        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            // "application_context" => [
            //     "return_url" => url('/successTransaction'),
            //     "cancel_url" => url('/cancelTransaction'),
            // ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $course->price
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {

            $transaction = new Transaction();

            $transaction->order_id = $response['id'];
            $transaction->user_id = auth()->user()->id;
            $transaction->course_id = $course_id;
            $transaction->amount = $course->price;
            $transaction->status = 'CREATED';
            $transaction->save();

            session(['transaction_id' => $transaction->id]);


            return response()->json([
                // 'response' => $response,
                'id' => $response['id'],
            ], 200);
        } else {
            return response()->json([
                'msg' => $response,
            ], 402);
        }
    }

    function paymentSuccess()
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();


        $transaction = Transaction::find(session('transaction_id'));

        $order = $provider->showOrderDetails($transaction->order_id);

        // dd($order);

        if ($order['status'] == 'COMPLETED') {
            $transaction->status = 'COMPLETED';
            $transaction->save();

            $courseTaken = new CourseTaken();
            $courseTaken->user_id = $transaction->user_id;
            $courseTaken->course_id = $transaction->course_id;
            $courseTaken->save();



            // giving payout (giving payment to instructor)
            $course = Course::find($transaction->course_id);
            $instructor = $course->instructor;
            $response = $this->payout($instructor->paypal_id, $course->price);
            // Logger($response);



            if (array_key_exists('batch_header', $response)) {
                $payout = new Payout();
                $payout->user_id = auth()->user()->id;
                $payout->course_id = $course->id;
                $payout->instructor_id = $instructor->id;
                $payout->payout_batch_id = $response['batch_header']['payout_batch_id'];
                $payout->save();
            }




            return [
                'status' =>  true,
                'order' => $order
            ];
        } else {
            return [
                'status' =>  false,
                'order' => $order
            ];
        }
    }
}
