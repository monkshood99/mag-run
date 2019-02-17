<?php
include './StravaPHP/vendor/autoload.php';

use Strava\API\OAuth;
use Strava\API\Exception;
use Strava\API\Service\REST;
use Strava\API\Client;


    $options = [
        'clientId'     => 32257,
        'clientSecret' => '045347e24a0a05aed3b68f9785f8eb514e8cc547',
        'redirectUri'  => 'http://magnoliarunning.com/strava/mg-st.php'
    ];
    $oauth = new OAuth($options);
    if (isset($_GET['code'])) {
        $token = $oauth->getAccessToken('authorization_code', ['code' => $_GET['code']]);
        $headers = array('Authorization: Bearer ' . $token->getToken());
        $curl = curl_init();

        $endpoint = 'activities';
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.strava.com/api/v3/{$endpoint}" ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . $token->getToken(),
            "cache-control: no-cache"
            ),
        ));
        $activities = curl_exec($curl);
        $activities = json_decode( $activities );

        // $endpoint = 'athlete';
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://www.strava.com/api/v3/{$endpoint}" ,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_POSTFIELDS => "",
        //     CURLOPT_HTTPHEADER => array(
        //     "Authorization: Bearer " . $token->getToken(),
        //     "cache-control: no-cache"
        //     ),
        // ));
        // $athlete = curl_exec($curl);
        // $athlete = json_decode( $activities );



    }
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    <script src="main.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.2.1/cerulean/bootstrap.min.css" rel="stylesheet" >
</head>
<body>  
    <div class = 'container'>
        <?php  if (!isset($_GET['code'])) { ?>
            <div class="row mt-5">
                <div class="col-4 offset-4">
                    <div class = 'card text-center py-5 px-5  shadow-lg rounded'>
                        <div class = 'card-title'>
                        <img src="https://cdn.road.cc/sites/default/files/styles/main_width/public/logostrava.png?itok=SRpR-u37" class = 'img-fluid'/>
                        </div>
                        <div class = 'card-body'>
                            <a  class = 'btn btn-xl btn-outline-danger' href="https://www.strava.com/oauth/authorize?redirect_uri=http%3A%2F%2Fmagnoliarunning.com%2Fstrava%2Fmg-st.php&response_type=code&scope=read,read_all,activity:read,activity:read_all,profile:read_all&client_id=32257">Connect</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <?php $values = $token->getValues();?>
            <div class="row">
                <div class="col-2">
                    <img src="https://cdn.road.cc/sites/default/files/styles/main_width/public/logostrava.png?itok=SRpR-u37" class = 'img-fluid'/>
                </div>
            </div>
            <hr/>

            <div class="row">
                <div class = 'col-3'>
                    <h4><?= $values['athlete']['firstname'];?> <?= $values['athlete']['lastname'];?></h4>
                    <img src="<?= $values['athlete']['profile'] ;?>" class = 'image-fluid img-thumbnail'/>
                </div>
                <div class="col-9">

                    <h4>Activities</h4>
                        <?php foreach( $activities as $activity ){ ?>
                            <div class = 'row ' >
                                <div class = 'col'>
                                    <div class = 'shadow-sm rounded py-2 px-3 mb-1'>
                                        <b><?= $activity->name ?> </b>| 
                                        <?=  ( $activity->distance  / 1609.34 ) ;?> miles
                                        <?=  gmdate("H:i:s", $activity->elapsed_time); ;?> 
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                </div>
            </div>
        <?php } ?>
    
    
    </div>


    
</body>
</html>