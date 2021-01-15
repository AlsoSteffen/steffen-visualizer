<?php
    $data_file = file_get_contents('tesla_stock.csv');

    $line_break = "\r\n";
    $column_break = ",";

    $exploded_data = explode($line_break, $data_file);

    $pair_line = [];

    $url = "http://localhost:8090/teslaStock";

    foreach ($exploded_data as $line)
    {
        $exploded_line = explode($column_break, $line);


        // set the variables that need to be added in the associative array
        
        $pair_line["date"] = $exploded_line[0];
        $pair_line["open"] = $exploded_line[1];
        $pair_line["high"] = $exploded_line[2];
        $pair_line["low"] = $exploded_line[3];
        $pair_line["close"] = $exploded_line[4];
        $pair_line["volume"] = $exploded_line[5];
        $pair_line["adjClose"] = $exploded_line[6];
        
        $line_json = json_encode($pair_line, JSON_UNESCAPED_SLASHES);
        
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => $line_json
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        print($line_json);

    }

?>