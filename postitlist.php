<?php
  try {

    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:postitlist.sqlite3');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);

    // Create table messages
    $file_db->exec("CREATE TABLE IF NOT EXISTS postitlist (item TEXT, creationdate TEXT)");

    // Select all data from file db messages table 
    $result = $file_db->query('SELECT * FROM postitlist');

    $rows = $result->fetchAll();

 if ($rows) {
    $response["items"]   = array();

    foreach ($rows as $row) {
        $item             = array();
        $item["item"] = $row["item"];
        $item["creationdate"] = $row["creationdate"];

        //update our repsonse JSON data
        array_push($response["items"], $item);
    }

    // echoing JSON response
    echo json_encode($response);
}

//print(json_encode($result->fetchAll()));

// Need this next line  since doing multiple PDO operations in a single functions
// without this line, the next request on file_db results in error "SQLSTATE[HY000]: General error: 6 database table is locked"
unset($result); 

    // Close file db connection
    $file_db = null;
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>
