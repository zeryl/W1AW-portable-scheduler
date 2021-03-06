<?php

    // configuration
    require("includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        foreach ( $_POST as &$p){
            $p = htmlspecialchars(trim($p));
        }
        $_POST["call"] = strtoupper($_POST["call"]);
        
        // validate submission
        if (empty($_POST["call"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }

        // query database for user
        $rows = query("SELECT * FROM op WHERE `callsign` = ?", $_POST["call"]);

        // if we found user, check password
        if (count($rows) == 1)
        {
            // first (and only) row
            $row = $rows[0];

            // compare hash of user's input against hash that's in database
            if (crypt($_POST["password"], $row["password"]) == $row["password"])
            {
                // remember that user's now logged in by storing user's ID in session
                $_SESSION["id"] = $row["id"];
                $_SESSION["call"] = $row["callsign"];
                $_SESSION["privilege"] = $row["privilege"];

                // redirect to home
                redirect("index.php");
            }
        }

        // else apologize
        apologize("Invalid call sign or password!");
    }
    else
    {
        // else render form
        render("login_form.php", array("title" => "Log In"));
    }

?>
