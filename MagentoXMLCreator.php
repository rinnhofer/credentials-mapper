<?php
/**
 * This is a proof of concept and should not be used as it is
 */

if (file_exists("input/credentials.json")) {
    // Get credentials file - for now it is considered to be json -
    // credentials should be separated from the other config keys since this is our focus and those fields are
    // consistent with the naming
    $credentialsFile = file_get_contents("input/credentials.json");
    $credentials = json_decode($credentialsFile, true);
} else {
    echo "Can not load credentials. No credentials.json provided!";
    return;
}
if (file_exists("input/config.xml")) {
    $xml = simplexml_load_file("input/config.xml");
    // Output of original config.xml
    echo "Original config.xml: <br>";
    print_r($xml);
    echo "<br>";
    echo "<br>";

    $default = $xml->default;
    $payment = $default->payment;

    // Here we can then select the payments to add the specific data - for now we are not looping through the payments
    // - we still would need to map the payment method names to the codes used within magento
    $paypal = $payment->wirecard_elasticengine_paypal;
    // Get the paypal credentials - we only want those for magento2
    $paypalCredentials = $credentials['paypal']['credentials'];

    // Add credential fields with defaultvalues
    foreach ($paypalCredentials as $configKey => $defaultValue) {
        $paypal->addChild($configKey, $defaultValue);
    }

    // Output of the adapted config.xml
    echo "config.xml with added credentials: <br>";
    print_r($xml);

    // Save new config.xml to output
    // $xml->asXML("output/config.xml");
    // This way saves the xml unformatted - therefor we have to use something else

    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    $dom->save("output/config.xml");
} else {
    echo "Can not load Magento2 Schema based xml. No config.xml file provided";
    return;
}
