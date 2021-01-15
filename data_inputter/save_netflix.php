<?php
    $data_file = file_get_contents('netflix.csv');

    $line_break = "\r\n";
    $column_break = ";";

    $exploded_data = explode($line_break, $data_file);

    $pair_line = [];

    $url = "http://localhost:8090/netflix";


    // this might exceed the maximum execution time set in php.ini
    foreach ($exploded_data as $line)
    {
        $exploded_line = explode($column_break, $line);


        // set the variables that need to be added in the associative array
        
        $pair_line["id"] = trim($exploded_line[0]);
        $pair_line["titleType"] = trim($exploded_line[1]);
        $pair_line["title"] = trim($exploded_line[2]);
        $pair_line["director"] = trim($exploded_line[3]);
        $pair_line["cast"] = trim($exploded_line[4]);
        $pair_line["country"] = trim($exploded_line[5]);
        $pair_line["dateAdded"] = trim($exploded_line[6]);
        $pair_line["releaseYear"] = trim($exploded_line[7]);
        $pair_line["rating"] = trim($exploded_line[8]);
        $pair_line["duration"] = trim($exploded_line[9]);
        $pair_line["category"] = trim($exploded_line[10]);
        $pair_line["description"] = trim($exploded_line[11]);
        
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
    }

?>