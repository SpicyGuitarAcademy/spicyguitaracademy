<?php
namespace App\Services;

class Paystack
{

	public function __construct()
	{
	}

	public function initiatePayment(int $paymentId, string $displayName, string $variableName, string $variableValue, string $paymentMedium, string $email, int $amount, string $callbackUrl, string $reference)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode([
				'amount'=>$amount,
				'email'=>$email,
				'callback_url'=>$callbackUrl,
				'reference'=>$reference,
				'metadata'=>[
					'payment_id'=>$paymentId,
					'payment_medium'=>$paymentMedium,
					'custom_fields'=>[
					   [
							'display_name'=>$displayName,
						 	'variable_name'=>$variableName,
						 	'value'=>$variableValue
					   ]
					]
				]
			]),
			CURLOPT_HTTPHEADER => [
				"authorization: Bearer " . PAYSTACK_PUBLIC_KEY, //replace this with your own test key
				"content-type: application/json",
				"cache-control: no-cache"
			],
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		if($err){
			// there was an error contacting the Paystack API
			// die('Curl returned error: ' . $err);

			// return error instead
			return [
				"flag"=>false,
				"error"=>"Curl returned error: " . $err
			];
		}
		
		$tranx = json_decode($response, true);

		if(!$tranx['status']){
			// there was an error from the API
			// print_r('API returned error: ' . $tranx['message']);

			// return error instead
			return [
				"flag"=>false,
				"error"=>"API returned error: " . $tranx['message']
			];
		}

		// comment out this line if you want to redirect the user to the payment page
		// print_r($tranx);

		// redirect to page so User can pay
		// uncomment this line to allow the user redirect to the payment page
		// header('Location: ' . $tranx['data']['authorization_url']);

		// return the url instead
		return [
			"flag"=>true,
			"data"=>$tranx['data']//['authorization_url']
		];
	}

	public function verifyPayment(string $reference)
	{
		$result = array();
		//The parameter after verify/ is the transaction reference to be verified
		$url = 'https://api.paystack.co/transaction/verify/' . $reference;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt(
		$ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . PAYSTACK_PUBLIC_KEY]
		);
		$request = curl_exec($ch);
		curl_close($ch);

		if ($request) {
			$result = json_decode($request, true);
			// print_r($result);
			if($result){
				if($result['data']){
					//something came in
					if($result['data']['status'] == 'success'){
						// the transaction was successful, you can deliver value
						/* 
						@ also remember that if this was a card transaction, you can store the 
						@ card authorization to enable you charge the customer subsequently. 
						@ The card authorization is in: 
						@ $result['data']['authorization']['authorization_code'];
						@ PS: Store the authorization with this email address used for this transaction. 
						@ The authorization will only work with this particular email.
						@ If the user changes his email on your system, it will be unusable
						*/
						return [
							"flag"=>true,
							"data"=>$result['data']
						];
					}else{
						// the transaction was not successful, do not deliver value'
						// print_r($result);  //uncomment this line to inspect the result, to check why it failed.
						return [
							"flag"=>false,
							"message"=>"Transaction was not successful: Last gateway response was: ".$result['data']['gateway_response']
						];
					}
				}else{
					// echo $result['message'];
					return [
						"flag"=>false,
						"message"=>$result['message']
					];
				}

			}else{
				//print_r($result);
				// die("Something went wrong while trying to convert the request variable to json. Uncomment the print_r command to see what is in the result variable.");
				return [
					"flag"=>false,
					"message"=>"Something went wrong while trying to convert the request variable to json. Uncomment the print_r command to see what is in the result variable."
				];
			}
		}else{
			//var_dump($request);
			// die("Something went wrong while executing curl. Uncomment the var_dump line above this line to see what the issue is. Please check your CURL command to make sure everything is ok");
			return [
				"flag"=>false,
				"message"=>"Something went wrong while executing curl. Uncomment the var_dump line above this line to see what the issue is. Please check your CURL command to make sure everything is ok"
			];
		}
	}

}

?>