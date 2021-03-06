<?php
    // configuration
    require("includes/config.php");

    
    if (isset($_GET["date"]))
    {
        $date = $_GET["date"];
    } 
    else
    {
        $now = new DateTime("Now", new DateTimeZone("UTC"));
        $today = $now->format("Y-m-d");

        //$today = "2013-01-01";

        $result = query("SELECT DISTINCT date FROM `slot` ORDER BY date");
        foreach ($result as $r){
            $cmp = strcmp($today, $r["date"]); 
            if ($cmp < 0) {
                $date = $r["date"];
                break;
            } else if ($cmp == 0) {
                $date = $today;
                break;
            } else {
                $date = $r["date"];
            }
        }
    }

    if (isset($_SESSION["id"])) {
        $op_id = $_SESSION["id"];
        $call = $_SESSION["call"];
    } else {
        $op_id = 1;
        $call = "Guest";
    }

    //dump($date);
    
    //get all the bands
    $result = query("SELECT * FROM band ORDER BY id");
    foreach ($result as $r) {
        $bands[] = array("id"=>$r["id"], "band"=>$r["band"], "modes"=>array());
    }

    foreach ($bands as &$b) { //each iteration is a band
        //get all modes for this band 
        $result = query("SELECT mode.mode as mode, mode.id as mode_id 
            FROM band_mode,mode 
            WHERE band_mode.band=? AND band_mode.mode=mode.id
            ORDER BY mode_id", $b["id"]);
        foreach ($result as $r) { //each iteration is one mode
            $result2 = query("SELECT slot.id as id, op.callsign as op, op.id as op_id,
                startTime, endTime
                FROM slot, op
                WHERE slot.date=? AND slot.band=? AND slot.mode=? AND slot.op=op.id
                ORDER BY startTime", $date, $b["id"], $r["mode_id"]);
            $slots = array();
            foreach ($result2 as $r2) {
                $slots[] = array(
                    "id"=>$r2["id"],
                    "time"=>$r2["startTime"], //Maybe not necessory
                    "endTime"=>$r2["endTime"], //Maybe not necessory
                    "op_id"=>$r2["op_id"],
                    "op"=>$r2["op"]
                );
            }
            $b["modes"][] = array(
                "mode"=>$r["mode"],
                "slots"=>$slots
            );
        }
    }

    $result = query("SELECT DISTINCT startTime,endTime FROM `slot` ORDER BY startTime");
    foreach ($result as $r){
        $times[] = array($r["startTime"], $r["endTime"]);
    }

    $result = query("SELECT DISTINCT date FROM `slot` ORDER BY date");
    foreach ($result as $r){
        $dates[] = $r["date"];
    }
    //dump($bands);


    //dump($_SERVER["REQUEST_URI"]);
    render("slot_template.php", array("title"=>"Time Slots - $call", "date" => $date,
        "times" => $times, "dates" => $dates, "bands"=>$bands, "url"=>$_SERVER["REQUEST_URI"]));
?>
