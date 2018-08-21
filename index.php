<?php
    require_once 'login.php';
    echo '<link rel="stylesheet" type="text/css" href="index.css"></head>';

    $conn = new mysqli($hn, $un, $pw, $db);

    if($conn->connect_error) die($conn->connect_error);

    if (isset($_POST['name'])        &&
        isset($_POST['description']) &&
        isset($_POST['contact'])     &&
        isset($_POST['id']))

    {
        $name = get_post($conn, 'name');
        $description = get_post($conn, 'description');
        $contact = get_post($conn, 'contact');
        $id = get_post($conn, 'id');
        $query =  "INSERT INTO userinfo VALUES".
            "('$name', '$description', '$contact', '$id')";
        $result = $conn->query($query);
        if (!$result) echo "INSERT failed: $query<br>" .
            $conn->error . "<br></br>";
    }

    echo <<<_END
    
<form action="index.php" method="post"><pre>
    <h2>Connecting people who have trucks with people who don't. </h2>
    <div class="formElements">
        <p>Name</p><input class="inputText" type="text" name="name" placeholder="ex. John Smith"> 
        <p>Vehicle</p><input class="inputText" type="text" name="description" placeholder="ex. Chevy Tahoe, Ford F-150"> 
        <p>Contact</p><input class="inputText" type="text" name="contact" placeholder="ex. 123-456-7890 or myemail@email.com"> 
        <p>Location</p><input class="inputText" type="text" name="id" placeholder="ex. Orlando, FL">
        <input  class="submit" type="submit" value="submit">
    </div>

</pre></form>
_END;

    echo "<h3 class='rightText'>Sometimes you need to pick something up that's just slightly to big for your car. Contact one of the people below 
                                to and see if they'll help you out.</h3>";


    $query = "SELECT * FROM userinfo";

    $result = $conn->query($query);
    if(!$result) die ("Database access failed: " . $conn->error);

    $rows = $result->num_rows;

    for($j = 0; $j < $rows; ++$j) {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_NUM);


    echo <<<_END
<div class="postStyle">
<pre>

<div class="postDiv">
    <p class="fieldDescription">Name: $row[0]</p>
    <p class="fieldDescription">Vehicle: $row[1]</p>
    <p class="fieldDescription">Conact: $row[2]</p>
    <p class="fieldDescription">Loacation: $row[3]</p>
</div>

<br>
</pre></div>
_END;
}

$result->close();
$conn->close();

function get_post($conn, $var) {
    return $conn->real_escape_string($_POST[$var]);
}

