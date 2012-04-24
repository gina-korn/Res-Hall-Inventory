<?php

//error_reporting(0);  // comment for debug\
@session_start();

class authenticate
{
	var $host = HOST;
    var $username = USER;
    var $password = PASSWORD;
    var $database = DBNAME;
 
    //table fields
    var $userTable = 'USER';
    var $userColumn = 'EMAIL';
    var $passwordColumn = 'encryptedPassword';
    var $userLevel = 'admin';
    
    
    //*********************************************************************************
 
    
    function dbConnect()
    {
        $connections = mysql_connect($this->host, $this->username, $this->password) or die ('Unable to connect to the database');
        mysql_select_db($this->database) or die ('Unable to select database!');
        return;
    }//end dbConnect
 
 
 	//*********************************************************************************
 	
 
    function login($email, $password)
    {
        //conect to DB
        $this->dbConnect();
        
        $password = md5($password);
        
        $result = $this->qry("SELECT * FROM ".$this->userTable." WHERE ".$this->userColumn." = '" . $email . "' AND " . $this->passwordColumn . " = '" . $password . "'");
        $row=mysql_fetch_assoc($result);
        
        if($row != "Error")
        {
            if($row[$this->userColumn] != "" && $row[$this->passwordColumn] != "")
            {
                $_SESSION['authenticated'] = 1;
                $_SESSION['isAdmin'] = $row[$this->userLevel];
                $_SESSION['email'] = $row[$this->userColumn];
                $_SESSION['password'] = $row[$this->passwordColumn];
				
				//Start of code modified by gina 3-12-2012 -- purpose grabing the ID and name of the CA for future purpose
					$f_name = $row['F_NAME'];
					$l_name = $row['L_NAME'];
					$_SESSION['OWName'] = $f_name . " " . $l_name;
					$_SESSION['OW_ID'] = $row['STUDENT_ID'];
					$_SESSION['AdminOrder'] = 100;
					$_SESSION['CAOrder'] = 200;
				// end of code modified by gina 3-12-2012
                return true;
            }
            
            else
            {
                session_destroy();
                return false;
            }
        }
        
        else
        {
            return false;
        }
 
    }//end login
    
    
    //*********************************************************************************
 
 
    function qry($query)
    {
    	$this->dbConnect();
      	$args  = func_get_args();
      	$query = array_shift($args);
      	$query = str_replace("?", "%s", $query);
      	$args  = array_map('mysql_real_escape_string', $args);
      	array_unshift($args,$query);
      	$query = call_user_func_array('sprintf',$args);
      	$result = mysql_query($query) or die(mysql_error());
        
        if($result)
        {
            return $result;
        }
        
        else
        {
             $error = "Error";
             return $result;
        }
    }//end qry
    
    
	//*********************************************************************************
 
 
    function loginCheck($userEmail, $userPassword)
    {
        $this->dbConnect();
        
                
        $result = $this->qry("SELECT * FROM " . $this->userTable . " WHERE " . $this->userColumn . " = '" . $userEmail . "' AND " .  $this->passwordColumn." = '" . $userPassword . "'");
        $rownum = mysql_num_rows($result);
        
        if($row != "Error")
        {
            if($rownum > 0)
            {
                return true;
            }
            
            else
            {
                return false;
            }
        }
    }//end loginCheck
    
    
    //*********************************************************************************
    
 
    function passwordReset($username, $userTable, $passwordColumn, $userColumn)
    {
        $this->dbConnect();
        $newpassword = $this->createPassword();
 
        if($this->passwordColumn == "")
        {
            $this->passwordColumn = $passwordColumn;
        }
        
        if($this->userColumn == "")
        {
            $this->userColumn = $userColumn;
        }
        
        if($this->userTable == "")
        {
            $this->userTable = $userTable;
        }
        

        $newpassword_db = md5($newpassword);
 
        $qry = "UPDATE ".$this->userTable." SET ".$this->passwordColumn."='".$newpassword_db."' WHERE ".$this->userColumn."='".stripslashes($username)."'";
        $result = mysql_query($qry) or die(mysql_error());
 
        $to = stripslashes($username);
        $illegals=array("%0A","%0D","%0a","%0d","bcc:","Content-Type","BCC:","Bcc:","Cc:","CC:","TO:","To:","cc:","to:");
        $to = str_replace($illegals, "", $to);
        $getemail = explode("@",$to);
 
        if(sizeof($getemail) > 2)
        {
            return false;
        }
        
        else
        {
            $from = $_SERVER['SERVER_NAME'];
            $subject = "Password Reset: ".$_SERVER['SERVER_NAME'];
            $msg = "Your new password is: " . $newpassword;
 
            $headers = "MIME-Version: 1.0 \r\n" ;
            $headers .= "Content-Type: text/html; \r\n" ;
            $headers .= "From: $from  \r\n" ;
 
            $sent = mail($to, $subject, $msg, $headers);
            
            if($sent)
            {
                return true;
            }
            
            else
            {
                return false;
            }
        }
    }//end passwordReset
	
	function changePassword($username, $userTable, $passwordColumn, $newPassword, $userColumn)
    {
        $this->dbConnect();
 
        if($this->passwordColumn == "")
        {
            $this->passwordColumn = $passwordColumn;
        }
        
        if($this->userColumn == "")
        {
            $this->userColumn = $userColumn;
        }
        
        if($this->userTable == "")
        {
            $this->userTable = $userTable;
        }
        

        $newpassword_db = md5($newpassword);
 
        $qry = "UPDATE ".$this->userTable." SET ".$this->passwordColumn."=MD5('".$newPassword."') WHERE ".$this->userColumn."='".stripslashes($username)."'";
        $result = mysql_query($qry) or die(mysql_error());
 
        $to = stripslashes($username);
        $illegals=array("%0A","%0D","%0a","%0d","bcc:","Content-Type","BCC:","Bcc:","Cc:","CC:","TO:","To:","cc:","to:");
        $to = str_replace($illegals, "", $to);
        $getemail = explode("@",$to);
 
        if(sizeof($getemail) > 2)
        {
            return false;
        }
        
        else
        {
            $from = $_SERVER['SERVER_NAME'];
            $subject = "Password Change: ".$_SERVER['SERVER_NAME'];
            $msg = "Your new password is: " . $newPassword;
 
            $headers = "MIME-Version: 1.0 \r\n" ;
            $headers .= "Content-Type: text/html; \r\n" ;
            $headers .= "From: $from  \r\n" ;
 
            $sent = mail($to, $subject, $msg, $headers);
            
            if($sent)
            {
                return true;
            }
            
            else
            {
                return false;
            }
        }
    }//end changePassword
    
    
    //*********************************************************************************
    
 
    function createPassword()
    {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;
        
        while ($i <= 7)
        {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        
        return $pass;
    }//end createPassword
    
    
    //*********************************************************************************
    
 
    function loginForm($formname, $formclass, $formaction)
    {
        $this->dbConnect();
        echo'
<form name="'.$formname.'" method="post" id="'.$formname.'" class="'.$formclass.'" enctype="application/x-www-form-urlencoded" action="'.$formaction.'">
<table>
<tr>
<td><label for="email">Email</label></td>
<td><input name="email" id="email" type="text"></td>
</tr>
<tr>
<td><label for="password">Password</label></td>
<td><input name="password" id="password" type="password"></td>
</tr>
</table>
<input name="action" id="action" value="login" type="hidden">

<input name="submit" id="submit" value="Login" type="submit">';

		if ($_GET['e'] == 1)
			echo '<div class="error">Your email or password was incorrect.</div>';
		
		echo "</form>";
 
    }//end loginForm
    
    
    //*********************************************************************************
    
    
    function resetForm($formname, $formclass, $formaction)
    {
        $this->dbConnect();
        echo'
<form name="'.$formname.'" method="post" id="'.$formname.'" class="'.$formclass.'" enctype="application/x-www-form-urlencoded" action="'.$formaction.'">
<div><label for="username">Username</label>
<input name="username" id="username" type="text"></div>
<input name="action" id="action" value="resetlogin" type="hidden">
<div>
<input name="submit" id="submit" value="Reset Password" type="submit"></div>
</form>
 
';
    }//end resetForm
    
    
    //*********************************************************************************
    
   
}//end authenticate class



?>