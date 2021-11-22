<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Laravie\Parser\Xml\Reader;
use Laravie\Parser\Xml\Document;

const XML_PATH = "./example.xml";
const XML_PUBLIC_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml?5105e8233f9433cf70ac379d6ccc5775';
const CURL_TIMEOUT = 50;

function curlXMLRequest()
{
    $fp = fopen (XML_PATH, 'w+');
    $ch = curl_init(XML_PUBLIC_URL);// or any url you can pass which gives you the xml file
    curl_setopt($ch, CURLOPT_TIMEOUT, CURL_TIMEOUT);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

function parseXMLWithLaravie()
{
    $xml = (new Reader(new Document()))->load(XML_PATH);

    $cubes = $xml->getContent();
    $array = json_decode(json_encode($cubes),TRUE);

    $values = $array["Cube"]["Cube"]["Cube"];

    $arrayResults = [];
    foreach ($values as $value){
        $arrayResults[] = [
            "currency" =>  $value["@attributes"]["currency"],
            "rate" => $value["@attributes"]["rate"]
        ];
    }

    echo json_encode($arrayResults);
}


curlXMLRequest();
parseXMLWithLaravie();


