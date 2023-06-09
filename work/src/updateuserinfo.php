<?php
    include 'header.php';
    include 'titlebar.php';

    if ($validity == "valid") {
        
        $col = $db -> users;
        
        if (isset($_POST['updateuser'])){
            $username = $_POST['username'];
            $userpwd = $_POST['userpassword'];
            //check and hash the password if reset
            $hashcheck = password_get_info($userpwd);
            if ($hashcheck['algoName'] === 'unknown'){
            	$hashpwd = password_hash($userpwd, PASSWORD_DEFAULT);
        	} else { $hashpwd = $userpwd; }
            
            $usergroups = $_POST['usergroups'];
            $userid = $_POST['userid'];
            if (isset($_POST['adminstatus'])) { $adminstatus = $_POST['adminstatus']; }
            else { $adminstatus = 'no'; }
            
            if (str_contains($usergroups, $username)){ }
            else { $usergroups = "".$usergroups.", ".$username.""; }
        
            $usereditquery = ['$set'=>['username' => $username, "password" => $hashpwd, 'groups' => $usergroups, 'adminstatus' => $adminstatus]];
            if ($updateUser = $col->updateOne(['_id' => new MongoDB\BSON\ObjectId("$userid")], $usereditquery)){
                if ($username == $loginuser && $userpwd != $loginpassword) {
                    $_SESSION['password'] = $userpwd;         
                }
                else if ($username != $loginuser && $userpwd == $loginpassword) {
                    $_SESSION['username'] = $username;     
                }
                echo "<h3>Success</h3><p class='postlink'><br>$username's info updated successfully<br><br></p>
                		<br><a href='noteshome.php'><button>< HOME</button></a>";
            }      
        }
        else if (isset($_POST['adduser'])){
            $username = $_POST['username'];
            $userpwd = $_POST['userpassword'];
            $hashpwd = password_hash($userpwd, PASSWORD_DEFAULT);
            $usergroups = $_POST['usergroups'];
            if (isset($_POST['adminstatus'])) { $adminstatus = $_POST['adminstatus']; }
            else { $adminstatus = 'no'; }
            
            if ($usergroups == "") { $usergroups = "".$username.""; }
            
            if (str_contains($usergroups, $username)){ }
            else { $usergroups = "".$usergroups.", ".$username.""; }

            $adduserquery = ['username' => $username, "password" => $hashpwd, 'groups' => $usergroups, 'adminstatus' => $adminstatus];
            if ($updateUser = $col->insertOne($adduserquery)){
                echo "<h3>Success</h3><p class='postlink'><br>$username's created successfully<br><br></p>
                		<br><a href='usermanagement.php'><button>< BACK</button></a>";
            }
        }
        else if (isset($_POST['userdel'])){
            $userid = $_POST['userid'];
            if ($col->deleteOne(['_id' => new MongoDB\BSON\ObjectId("$userid")])){
                echo "<h3>User deleted successfully. </h3>
                		<br><a href='usermanagement.php'><button>< BACK</button></a>";
            }
        }

    }

    else { echo "Sorry, username and password are unknown. Please try to log in again."; }
    
?>

</td></tr></table></body></html>
