<?php 

require_once("Paypal-PHP-SDK/autoload.php");

$apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'ATdXqeDrJPkD9W0WbtodV2OpgUP6OUNXKSU5ysW-mR8V954bPaFp4uo1HZcNrfBb61N0WZC2JW5JyTk6',     // ClientID
            'EOxz-d_58DdYa5Y5ByKuMaWBo0esgbwHXgcIaHgXDY59_TWzfIML6bDD-6o1XY4tIRVOmcDFj_7oX1wc'      // ClientSecret
        )
);

?>