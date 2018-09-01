<?php
/**
 * Created by PhpStorm.
 * User: dkliukin
 * Date: 7/30/18
 * Time: 3:10 PM
 */

header("Access-Control-Allow-Origin: *");
//header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

require __DIR__."/../boot/app.php";


$app->run();