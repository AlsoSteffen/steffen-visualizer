<?php

function getXmlNetflixCountBasedOnDate($date)
{
    $path = "schema/netflix/netflix_schema.xsd";
    return validateXmlFile(file_get_contents("http://localhost:8090/netflix/count=" . $date), $path);
}

function getXmlTweetCountBasedOnDate($date)
{
    $path = "schema/tweets/tweets_schema.xsd";
    return validateXmlFile(file_get_contents("http://localhost:8090/tweet/count=" . $date), $path);
}

function getXmlTeslaStockBasedOnDate($date)
{
    $path = "schema/tesla/tesla_schema.xsd";
    return validateXmlFile(file_get_contents("http://localhost:8090/teslaStock/xml/date=" . $date), $path);
}

/**
 * This method was supposed to validate xml from the api, however
 * Validation fails because of the way the api constructs its files
 * I cannot fix this with my current ability, maybe someday - steffen
 *
 * @param $file string - location of the schema file
 * @param $schema string - location of the schema file
 * @return DOMDocument - Xml document returned if file is valid
 */
function validateXmlFile($file, $schema)
{
    $xml = new DOMDocument($file);
    $xml->loadXML($file, LIBXML_NOBLANKS);

    if (!$xml->schemaValidate($schema))
    {
        printf("There was an error in the xml file");
    }

    return $xml;
}
