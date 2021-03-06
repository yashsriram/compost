<?php
    require '../gateWay/.MySQLServer/credentials.php';
    require '../gateWay/signUpPage/clubColumnData.php';
    
    $dbname = "groups";
    $tableName = urldecode($_POST['group']);

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) 
        {
        die("Connection failed: " . mysqli_connect_error());
        }

    $sql = "SELECT * FROM $tableName ";
    $result = mysqli_query($conn, $sql);
    
    $response = array();
    // GROUP TABLE
    if(mysqli_num_rows($result) > 0) 
        {
        while($row = mysqli_fetch_assoc($result)) 
            {
            array_push($response , 
                                    array( 
                                $GROUP_COL[0]=>$row[$GROUP_COL[0]] , 
                                $GROUP_COL[1]=>$row[$GROUP_COL[1]] ,
                                $GROUP_COL[2]=>$row[$GROUP_COL[2]] , 
                                $GROUP_COL[3]=>$row[$GROUP_COL[3]] ,
                                $GROUP_COL[4]=>$row[$GROUP_COL[4]] , 
                                $GROUP_COL[5]=>$row[$GROUP_COL[5]] , 
                                $GROUP_COL[6]=>$row[$GROUP_COL[6]] , 
                                $GROUP_COL[7]=>$row[$GROUP_COL[7]] , 
                                $GROUP_COL[8]=>$row[$GROUP_COL[8]] ,
                                $GROUP_COL[9]=>$row[$GROUP_COL[9]] , 
                                $GROUP_COL[10]=>$row[$GROUP_COL[10]] , 
                                $GROUP_COL[11]=>$row[$GROUP_COL[11]] ,
                                $GROUP_COL[12]=>$row[$GROUP_COL[12]] , 
                                $GROUP_COL[13]=>$row[$GROUP_COL[13]] , 
                                $GROUP_COL[14]=>$row[$GROUP_COL[14]] , 
                                $GROUP_COL[15]=>$row[$GROUP_COL[15]] , 
                                $GROUP_COL[16]=>$row[$GROUP_COL[16]] , 
                                $GROUP_COL[17]=>$row[$GROUP_COL[17]] 
                                      )
                      );
            }
        } 
    else 
        {
        echo "0 results";
        }
    echo json_encode(array("server_response"=>$response));
    
    mysqli_close($conn);
?>

