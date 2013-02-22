<?php

// Test if submitted IP is valid
function test_ip($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return 1;
        } else {
            return 0;
        }
}

// Display Gallery off 504 items (Why 504 ? it's optimised for my screen :)))
function chv_gallery($bdd,$ip = 0,$site_url = "localhost") {
        $tall = "width=100px height=90px";

        if($ip && test_ip($ip))
        {
                $resultat = $bdd->query("SELECT image_name, image_type, image_size, image_date, uploader_ip FROM chv_images WHERE uploader_ip LIKE \"$ip\" ORDER BY image_id DESC LIMIT 0,504");
        } else {
                $resultat = $bdd->query("SELECT image_name, image_type, image_size, image_date, uploader_ip FROM chv_images ORDER BY image_id DESC LIMIT 0,504");
        }
        $resultat->setFetchMode(PDO::FETCH_OBJ);


        // Pics per line
        $ppl = 10;
        // Pics counter
        $pcc = 0;

        echo "<table border=1>";
        echo "<th colspan='100%'>Gallery pics</th>";
        while( $ligne = $resultat->fetch() )
        {
                $day = date("d",strtotime($ligne->image_date));
                $month = date("m",strtotime($ligne->image_date));
                $year = date("Y",strtotime($ligne->image_date));
                $image_name = $ligne->image_name;

                $file_th = $year."/".$month."/".$day."/".$image_name.".th.".$ligne->image_type;
                $file_big = $year."/".$month."/".$day."/".$image_name.".".$ligne->image_type;

                $href_alt = "Date : ".$ligne->image_date." | IP : ".$ligne->uploader_ip;
                if($pcc == 0)
                {
                        echo "<tr>";
                        echo "<td><a title=\"$href_alt\" target=\"_blank\" href=\"http://$site_url/images/".$file_big."\"><img $tall src=\"http://www.uploadfr.com/images/".$file_th."\"></a></td>";
                        $pcc++;
                } elseif($pcc != 0 && $pcc <= $ppl) {
                        echo "<td><a title=\"$href_alt\" target=\"_blank\" href=\"http://$site_url/images/".$file_big."\"><img $tall src=\"http://www.uploadfr.com/images/".$file_th."\"></a></td>";
                        $pcc++;
                } else {
                        echo "<td><a title=\"$href_alt\" target=\"_blank\" href=\"http://$site_url/images/".$file_big."\"><img $tall src=\"http://www.uploadfr.com/images/".$file_th."\"></a></td>";
                        echo "</tr>";
                        $pcc = 0;
                }

        }
        echo "</table>";
}

// Display latests pics submitted
function chv_latest($bdd,$ip = 0,$site_url = "localhost") {
        if($ip && test_ip($ip))
        {
                $resultat = $bdd->query("SELECT image_name, image_type, image_size, image_date, uploader_ip FROM chv_images WHERE uploader_ip LIKE \"$ip\" ORDER BY image_id DESC LIMIT 0,10");
        } else {
                $resultat = $bdd->query("SELECT image_name, image_type, image_size, image_date, uploader_ip FROM chv_images ORDER BY image_id DESC LIMIT 0,10");
        }
        $resultat->setFetchMode(PDO::FETCH_OBJ);


        echo "<table border=1>";
        echo "<th colspan=2>Latest 10 pics</th>";
        while( $ligne = $resultat->fetch() )
        {
                $day = date("d",strtotime($ligne->image_date));
                $month = date("m",strtotime($ligne->image_date));
                $year = date("Y",strtotime($ligne->image_date));
                $image_name = $ligne->image_name;

                $file_th = $year."/".$month."/".$day."/".$image_name.".th.".$ligne->image_type;
                $file_big = $year."/".$month."/".$day."/".$image_name.".".$ligne->image_type;

                echo "<tr>";
                if(!$ip) echo "<td><a target=\"_blank\" href=\"http://$site_url/images/".$file_big."\"><img src=\"http://$site_url/images/".$file_th."\"></a></td>";
                echo "<td>";
                echo "Date : ".$ligne->image_date."<br/>";
                echo "IP : <a href='?ip=".$ligne->uploader_ip."'>".$ligne->uploader_ip."</a>";
                echo "</td>";
                echo "</tr>";
        }
        echo "</table>";
}

// Display top  15 items directly showed on chevereto.
function chv_stats($bdd) {
        $resultat = $bdd->query("SELECT * FROM chv_stats ORDER BY hits DESC LIMIT 0,15");
        $resultat->setFetchMode(PDO::FETCH_OBJ);

        echo "<table border=1>";
        echo "<th colspan=2>Top 15 hits ref</th>";
        while( $ligne = $resultat->fetch() )
        {
                echo "<tr>";
                $explode = explode(".",$ligne->url);
                echo "<td><a target=\"_blank\" href=\"http://".$ligne->site.$ligne->url."\"><img src=\"http://".$ligne->site.$explode[0].".th.".$explode[1]."\"></a></td>";
                echo "<td>".$ligne->hits." hits</td>";
                echo "</tr>";
        }
        echo "</table>";
}

// Display top IP uploaders
function chv_ip($bdd) {
        $result_at = $bdd->query("SELECT uploader_ip, count(image_id) AS counter FROM chv_images GROUP BY uploader_ip ORDER BY counter DESC LIMIT 0,10");
        $result_at->setFetchMode(PDO::FETCH_OBJ);

        $date = date("Y-m-d ", time())."00:00:00";
        $result_week = $bdd->query("SELECT uploader_ip, count(image_id) AS counter FROM chv_images WHERE image_date > \"$date\" GROUP BY uploader_ip ORDER BY counter DESC LIMIT 0,10");
        $result_week->setFetchMode(PDO::FETCH_OBJ);


        echo "<table border=1>";
        echo "<th colspan=2>Top 10 IP All Time</th>";
        while( $ligne_at = $result_at->fetch() )
        {
                echo "<tr>";
                echo "<td><a href='?ip=".$ligne_at->uploader_ip."'>".$ligne_at->uploader_ip."</a></td>";
                echo "<td>".$ligne_at->counter." elements</td>";
                echo "</tr>";
        }
        echo "<th colspan=2>Top 10 IP H24</th>";
        while( $ligne_week = $result_week->fetch() )
        {
                echo "<tr>";
                echo "<td><a href='?ip=".$ligne_week->uploader_ip."'>".$ligne_week->uploader_ip."</a></td>";
                echo "<td>".$ligne_week->counter." elements</td>";
                echo "</tr>";
        }
        echo "</table>";
}

?>
