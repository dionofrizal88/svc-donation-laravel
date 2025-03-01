<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use App\Models\Donation;
use Midtrans\Config;
use Midtrans\Snap;

class DonationController extends Controller
{
    private $response;

    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }


    public function createDonation(Request $request)
    {
        $this->response = [];

        try {
            $request->validate([
                'amount' => 'required|numeric',
                'donor_name' => 'required|string',
                'donor_email' => 'required|string',
                'donation_type' => 'required|string', 
            ]);

            DB::transaction(function() use($request) {
                $donation = Donation::create([
                    'donor_name' => $request->donor_name,
                    'donor_email' => $request->donor_email,
                    'donation_type' => $request->donation_type,
                    'amount' => floatval($request->amount),
                    'note' => $request->note,
                ]);

                $payload = [
                    'transaction_details' => [
                        'order_id'      => 'donation-' . $donation->id,
                        'gross_amount'  => $donation->amount,
                    ],
                    'customer_details' => [
                        'first_name'    => $donation->donor_name,
                        'email'         => $donation->donor_email,
                    ],
                    'item_details' => [
                        [
                            'id'       => $donation->donation_type,
                            'price'    => $donation->amount,
                            'quantity' => 1,
                            'name'     => ucwords(str_replace('_', ' ', $donation->donation_type))
                        ]
                    ]
                ];

                $snapToken = Snap::getSnapToken($payload);

                $donation->snap_token = $snapToken;
                $donation->order_id = 'donation-' . $donation->id;
                $donation->save();

                $this->response['message'] = "Donation success";
                $this->response['data'] = $donation;
            });

            return response()->json($this->response, 201);
        } catch (ValidationException $e) {

            return response()->json([
                'error' => 'Validation error',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Midtrans Donation Error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'There was an error processing your donation. Please try again later.',
            ], 500);
        }
    }

    public function createDonationPayment(Request $request)
    {
    
        try {
            $request->validate([
                'amount' => 'required|numeric',
                'payment_method' => 'required|string',
                'snap_token' => 'required|string',
                'order_id' => 'required|string', 
            ]);
    
            $midtransServerKey = config('midtrans.server_key'); 
            $mintransUrl = config('midtrans.base_url') . '/v2/charge';
    
            $paymentData = [
                'payment_type' => $request->payment_method,
                'transaction_details' => [
                    'order_id' => $request->order_id, 
                    'gross_amount' => $request->amount,
                ],
                'credit_card' => [
                    'token_id' => $request->snap_token,
                    'authentication' => true,
                ],
            ];
            
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($midtransServerKey . ':'), 
                'Content-Type' => 'application/json',
            ])->post($mintransUrl, $paymentData);

            if ($response->successful()) {
                return response()->json([
                    'message' => 'Donation created successfully',
                    'data' => $response->json(),
                ], 200);
            }

            return response()->json([
                'error' => 'Failed to create donation',
                'details' => $response->json(),
            ], 500);

        } catch (ValidationException $e) {

            return response()->json([
                'error' => 'Validation error',
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Midtrans Donation payment Error: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your donation',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
