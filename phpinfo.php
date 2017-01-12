<?php
$dbname = "assay_catalog";
$host = "localhost";
$dbuser = "assay_manager";
$dbpass = "df1funi";
$dbh = new PDO("pgsql:dbname=$dbname;host=$host", $dbuser, $dbpass);
$sth = $dbh->prepare("SELECT * FROM account WHERE login='sancho'");
$sth->execute();
date_default_timezone_set("Asia/Yekaterinburg");
foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row):
    print $row['insert_date'];
endforeach;
print "\n";
$z = time();
print $z;
print "\n";
print date('c',$z);