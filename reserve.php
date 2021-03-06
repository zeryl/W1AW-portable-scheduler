<?php
    require("includes/config.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {    
        $op_id = $_SESSION["id"];
        $privilege = $_SESSION["privilege"];
        //dump($_POST);
        if ($privilege > 1){ //if you are admin op you can cancel other's slot
            $result = query("UPDATE slot SET op=? WHERE id=? AND (op=0 OR ?=0)", 
                $_POST["op"],$_POST["id"],$_POST["op"]);
        } else { //normal op can only take free slot and cancel your own slot
            $result = query("UPDATE slot SET op=? WHERE id=? AND (op=0 OR op=?)", 
                $_POST["op"],$_POST["id"],$_SESSION["id"]);
        }
        if ($result === false) {
            apologize("Failed to take/cancel slot! Please try again later.");
        } else {
            redirect($_POST["url"]);
        }

    }else
    {
        redirect("index.php");
    }


?>
