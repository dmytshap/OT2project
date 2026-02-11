
            <?php
                $dbservername = "mariadb"; 
                $dbusername = "root"; 
                $dbpassword = "projekti"; 
                $dbname = "PROJECTS"; 
                
                try{
                    $connection = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
                }catch(Exception $e){
                    $error = $e->getMessage();
                    var_dump($error);
                }

                $sql = 'SELECT EMAIL FROM PROJECT_DATA';
                $result = mysqli_query($connection, $sql);

                $num_of_projects = mysqli_num_rows($result);

                if($num_of_projects > 0){
                $projects_arr = array();
                    $projects_arr['data'] = array();
                    while($row = $statement->fetch_assoc()){
                        $project_item = array(
                            'EMAIL' => $email,
                        );
                        array_push($projects_arr['data'], $project_item_item);
                    }
                    echo json_encode($projects_arr);
                }else{
                    echo 'Ei projekteja';
                }
            ?>

