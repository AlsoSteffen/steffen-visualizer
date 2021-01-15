<?php
    $data_file = file_get_contents('tweet.csv');

    $line_break = "\r\n";
    $column_break = ";";

    $exploded_data = explode($line_break, $data_file);

    $pair_line = [];

    $url = "http://localhost:8090/tweet";


    // this might exceed the maximum execution time set in php.ini
    // this takes a long time to execute (really long lmao)
    foreach ($exploded_data as $line)
    {
        $exploded_line = explode($column_break, $line);


        // set the variables that need to be added in the associative array
        
        $pair_line["id"] = trim($exploded_line[0]);
        $pair_line["link"] = trim($exploded_line[1]);
        $pair_line["content"] = trim($exploded_line[2]);
        $pair_line["date"] = trim($exploded_line[3]);
        $pair_line["retweets"] = trim($exploded_line[4]);
        $pair_line["favorites"] = trim($exploded_line[5]);
        $pair_line["mentions"] = trim($exploded_line[6]);
        $pair_line["tags"] = trim($exploded_line[7]);
        
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