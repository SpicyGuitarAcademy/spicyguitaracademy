<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;

class PayPalClient
{

  /**
   * Returns PayPal HTTP client instance with environment that has access
   * credentials context. Use this instance to invoke PayPal APIs, provided the
   * credentials have access.
   */
  public static function client()
  {
    return new PayPalHttpClient(self::environment());
  }

  /**
   * Set up and return PayPal PHP SDK environment with PayPal access credentials.
   * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
   */
  public static function environment()
  {
    $clientId = PAYPAL_CLIENT_ID; // getenv("CLIENT_ID") ?: "PAYPAL-SANDBOX-CLIENT-ID";
    $clientSecret = PAYPAL_SECRET_KEY; // getenv("CLIENT_SECRET") ?: "PAYPAL-SANDBOX-CLIENT-SECRET";
    return PAYPAL_MODE == 'TEST' ? new SandboxEnvironment($clientId, $clientSecret) : new ProductionEnvironment($clientId, $clientSecret);
  }

  // 2. Set up your server to receive a call from the client
  /**
   *This is the sample function to create an order. It uses the
   *JSON body returned by buildRequestBody() to create an order.
   */
  public static function initiatePayment(string $displayName, float $amountInDollar, string $reference, string $variableValue)
  {
    try {
      $request = new OrdersCreateRequest();
      $request->prefer('return=representation');
      $request->body = array(
        'intent' => 'CAPTURE', // CAPTURE, AUTHORIZE
        'application_context' =>
        array(
          'brand_name' => 'Spicy Guitar Academy',
          'return_url' => SERVER . '/api/subscription/paypal/verify',
          'cancel_url' => SERVER . '/api/subscription/paypal/verify',
          'user_action' => 'PAY_NOW'
        ),
        'purchase_units' =>
        array(
          0 =>
          array(
            'reference_id' => $reference,
            'description' => $displayName,
            'custom_id' => $variableValue,
            'amount' =>
            array(
              'currency_code' => 'USD',
              'value' => $amountInDollar
            )
          )
        )
      );

      // 3. Call PayPal to set up a transaction
      $client = PayPalClient::client();
      $response = $client->execute($request);

      // 4. Return a successful response to the client.
      $response = json_decode(json_encode($response), true);

      $links = $response['result']['links'];
      $approveLink = array_filter($links, function ($link) {
        return $link['rel'] == 'approve';
      });
      $response['result']['authorization_url'] = array_values($approveLink)[0]['href'];
      $response['result']['reference'] = $response['result']['purchase_units'][0]['reference_id'];

      return $response;
    } catch (\PayPalHttp\HttpException $th) {
      $error =  json_decode($th->getMessage(), true);
      return ["error" => $error['error_description']];
    }
  }

  // 2. Set up your server to receive a call from the client
  /**
   *You can use this function to retrieve an order by passing order ID as an argument.
   */
  public static function verifyPayment($orderId)
  {
    try {
      // 3. Call PayPal to get the transaction details
      $client = PayPalClient::client();
      $response = $client->execute(new OrdersGetRequest($orderId));

      // 4. Save the transaction in your database.
      // Implement logic to save transaction to your database for future reference.
      // print "Gross Amount: {$response->result->purchase_units[0]->amount->currency_code} {$response->result->purchase_units[0]->amount->value}\n";

      // To print the whole response body, uncomment the following line
      // echo json_encode($response->result, JSON_PRETTY_PRINT);
      $response = json_decode(json_encode($response), true);

      $link = $response['result']['links'][0]['href'];
      $response['result']['domain'] = PAYPAL_MODE == 'LIVE' ? 'live' : 'test'; //\str_contains($link, 'sandbox') ? 'test' : 'live';

      return $response;
    } catch (\PayPalHttp\HttpException $th) {
      $error =  json_decode($th->getMessage(), true);
      return ["error" => $error['error_description']];
    }
  }

  // 2. Set up your server to receive a call from the client
  // Use this function to perform a refund on the capture.

  // RefundOrder::refundOrder('<REPLACE-WITH-VALID-CAPTURE-ID>', true);

  public static function refundOrder($captureId, $debug = false)
  {
    $request = new CapturesRefundRequest($captureId);
    $request->body = array(
      'amount' =>
      array(
        'value' => '20.00',
        'currency_code' => 'USD'
      )
    );
    // 3. Call PayPal to refund a capture
    $client = PayPalClient::client();
    $response = $client->execute($request);

    if ($debug) {
      // print "Status Code: {$response->statusCode}\n";
      // print "Status: {$response->result->status}\n";
      // print "Order ID: {$response->result->id}\n";
      // print "Links:\n";
      // foreach ($response->result->links as $link) {
      //   print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
      // }
      // To toggle printing the whole response body comment/uncomment
      // the following line
      echo json_encode($response->result, JSON_PRETTY_PRINT), "\n";
    }
    return $response;
  }
}
