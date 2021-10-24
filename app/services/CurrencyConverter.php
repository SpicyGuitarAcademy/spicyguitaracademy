<?php

namespace App\Services;

class CurrencyConverter
{
  public static function getNairaToDollarRate()
  {
    try {
      // set API Endpoint and API key
      $endpoint = 'latest';
      $access_key = EXCHANGERATEAPI_ACCESS_KEY;

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "http://api.exchangeratesapi.io/v1/$endpoint?access_key=$access_key&symbols=NGN,USD",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET"
      ));

      // Store the data:
      $json = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);

      // Decode JSON response:
      $exchangeRates = json_decode($json, true);

      if ($err) {
        return 410.00;
      } else {
        if ($exchangeRates['success'] == true) {
          // Access the exchange rate values, e.g. GBP:
          // return number_format((float)($exchangeRates['rates']['NGN'] / $exchangeRates['rates']['USD']), 2, '.', ' ');
          return round(($exchangeRates['rates']['NGN'] / $exchangeRates['rates']['USD']), 2);
        } else {
          return 410.00;
        }
      }
    } catch (\Throwable $th) {
      return 410.00;
    }
  }
}


// CurrencyConverter::getNairaToDollarRate();
