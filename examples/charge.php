<?php 

require_once __DIR__ . '/../vendor/autoload.php';

$config = parse_ini_file(__DIR__ . '/../config.ini');


// Criar uma cobrança via boleto
try {

    $auth = new \ODJuno\ODJunoAuth($config);
    $authorization = $auth->authService()->authenticate();

    $juno = new \ODJuno\ODJuno($authorization->getAccessToken(), $config['privateToken']);

    $charge = new \ODJuno\Entities\Charge();

    $charge->getChargeDetail()
        ->setDescription("Charge Description")
        ->setPaymentTypes([\ODJuno\Entities\ChargeDetail::PAYMENT_TYPE_BOLETO])
        ->setAmount(100);

    $charge->getBilling()
        ->setName("Billing Name")
        ->setDocument("25113448079")
        ->setEmail("fernando@odesenvolvedor.net")
        ->getAddress()
            ->setStreet("Street")
            ->setNumber("N/A")
            ->setComplement("Complement")
            ->setNeighborhood("Neighborhood")
            ->setCity("City")
            ->setState("State")
            ->setPostCode("9999999");

    $result = $juno->charges()->create($charge);

    print_r($result);

} catch (\GuzzleHttp\Exception\RequestException $e) {
    if ($e->hasResponse()) {
        $response = $e->getResponse();
        var_dump($response->getStatusCode()); // HTTP status code;
        var_dump($response->getReasonPhrase()); // Response message;
        var_dump(json_decode((string) $response->getBody())); // Body as the decoded JSON;
    }
}


// Consultar uma cobrança
try {

    $auth = new \ODJuno\ODJunoAuth($config);
    $authorization = $auth->authService()->authenticate();
    $juno = new \ODJuno\ODJuno($authorization->getAccessToken(), $config['privateToken']);

    $result = $juno->charges()->show('charge_id');

    print_r($result);

} catch (\GuzzleHttp\Exception\RequestException $e) {
    if ($e->hasResponse()) {
        $response = $e->getResponse();
        var_dump($response->getStatusCode()); // HTTP status code;
        var_dump($response->getReasonPhrase()); // Response message;
        var_dump(json_decode((string) $response->getBody())); // Body as the decoded JSON;
    }
}