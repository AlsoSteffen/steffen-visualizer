<?php

require __DIR__ . '/vendor/autoload.php';

use JsonSchema\Validator;

function getJsonNetflixCountBasedOnDate($date)
{
    $path = "schema/netflix/netflix_schema.json";
    return validateJsonFile(file_get_contents("http://localhost:8090/netflix/count=" . $date), $path);
}

function getJsonTweetCountBasedOnDate($date)
{
    $path = "schema/tweets/tweets_schema.json";
    return validateJsonFile(file_get_contents("http://localhost:8090/tweet/count=" . $date), $path);
}

function getJsonTeslaStockBasedOnDate($date)
{
    $path = "schema/tesla/tesla_schema.json";
    return validateJsonFile(file_get_contents("http://localhost:8090/teslaStock/json/date=" . $date), $path);
}

/**
 * Validator function for json files
 *
 * I did not create this validation method, nor the files associated with it
 * see https://github.com/justinrainbow/json-schema for the original code
 *
 * @param $file string filepath to json
 * @param $schema string filepath to schema
 * @return mixed returns the file validated
 *
 * Make sure you have composer installed and that you run the
 * composer install command in the terminal to install needed
 * libraries
 */
function validateJsonFile($file, $schema)
{
    // temp saving a non-object version so I don't need to retrieve data from an
    // object but instead just need to validate the object version of this to know
    // if this one is valid or not - steffen
    $unparsedFile = json_decode($file);

    // parsing the file to an object so the validator can see it - steffen
    $file = (object)json_decode($file);

    $validator = new Validator();

    $validator->validate($file, (object)['$ref' => 'file://' . realpath($schema)]);

    if ($validator->isValid())
    {
        return $unparsedFile;
    } else
    {
        echo "Json validation errors";
        foreach ($validator->getErrors() as $error)
        {
            printf("[%s] %s\n", $error['property'], $error['message']);
        }
    }
}

/**
 * Gets the Adjusted Close stock price for teslaStocks
 * @param $tesla stdClass object acquired from API
 * @return double - the value of the adjClose price
 */
function getAdjClose($tesla)
{
    foreach ($tesla as $item)
    {
        foreach ($item as $value => $i)
        {
            if ($value == 'adjClose')
            {
                return $i;
            }
        }
    }
    return null;
}

/**
 * Prints out the needed values for the chart
 * @param $startDate string - starting date for data display (in yyyy-MM-dd format)
 * @param $endDate string - end date for data display (in yyyy-MM-dd format)
 * @return string returns a string in array format for Google charts.
 */
function getDataWithStartingDateFromJsonFiles($startDate, $endDate)
{
    // final string being returned
    $string = "['Date', 'Number of Trump Tweets', 'Number of Netflix Releases', 'Tesla Close Price'], ";

    try
    {
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);

        $dateDiff = $startDate->diff($endDate);

        for ($i = 0; $i < $dateDiff->days; $i++)
        {
            $stock = getJsonTeslaStockBasedOnDate($startDate->format("d-m-Y"));
            $tweet = getJsonTweetCountBasedOnDate($startDate->format("d-m-Y"));
            $netflix = getJsonNetflixCountBasedOnDate($startDate->format("d-m-Y"));
            if ($stock != null)
            {
                $string .= "['" . $startDate->format("d-m-Y") . "', " . $tweet . ", " . $netflix . ", " . getAdjClose($stock) . "], ";
            }

            $startDate->add(new DateInterval("P1D"));
        }
        return $string;
    }
    catch (Exception $e)
    {
        return "invalid data input please use format yyyy-MM-dd";
    }
}
