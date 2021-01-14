<?php
function getNetflixCoundBasedOnDate($date)
{
    return file_get_contents("http://localhost:8090/netflix/count=" . $date);
}

function getTweetCountBasedOnDate($date)
{
    return file_get_contents("http://localhost:8090/tweet/count=" . $date);
}

function getTeslaStockBasedOnDate($date)
{
    return json_decode(file_get_contents("http://localhost:8090/teslaStock/json/date=" . $date));
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
function getDataWithStartingDate($startDate, $endDate)
{
    $string = "['Date', 'Number of Trump Tweets', 'Number of Netflix Releases', 'Tesla Close Price'], ";

    try
    {
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $dateDiff = $startDate->diff($endDate);
        $originalDiff = $dateDiff->days;
        $dateDiff->d = 1;


        for ($i = 0; $i < $originalDiff; $i++)
        {
            $stock = getTeslaStockBasedOnDate($startDate->format("d-m-Y"));
            $tweet = getTweetCountBasedOnDate($startDate->format("d-m-Y"));
            $netflix = getNetflixCoundBasedOnDate($startDate->format("d-m-Y"));
            if ($stock != null)
            {
                $string .= "['" . $startDate->format("d-m-Y") . "', " . $tweet . ", " . $netflix . ", " . getAdjClose($stock) . "], ";
            }

            $startDate->add($dateDiff);
        }
        return $string;
    }
    catch (Exception $e)
    {
        return "invalid data input please use format yyyy-MM-dd";
    }
}