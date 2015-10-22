<?php
require '../vendor/autoload.php';
$app = new  \Slim\Slim();

$app->get('/getdata/:lat/:lng/:radius',  'get_data');
$app->get('/securedata/:lat/:lng/:radius/:token',  'secure_get_data');
$app->get('/validate_key/:key',  'validate_key');
$app->post('/login', 'login');

$app->run();


function getConnection() {
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="menu";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}



function get_data($lat,$lng,$radius){
       // $radius = 12000;
        //$cordinate = get_geometry($address);
       // $query = "select *from markers where GeoDistDiff('mi','".$cordinate['lat']."','".$cordinate['lon']."',lat,lng)<".$radius;
        $query = "select *from markers where GeoDistDiff('km','".$lat."','".$lng."',lat,lng)<".$radius;
        $db = getConnection();
        $stmt = $db->query($query);
        $all_cities = $stmt->fetchAll(PDO::FETCH_OBJ);
        //$db = null;
        echo json_encode($all_cities);
}

function secure_get_data($lat,$lng,$radius,$key){
       // $radius = 12000;
        //$cordinate = get_geometry($address);
       // $query = "select *from markers where GeoDistDiff('mi','".$cordinate['lat']."','".$cordinate['lon']."',lat,lng)<".$radius;
        if(validate_key($key)){
        $query = "select *from markers where GeoDistDiff('mi','".$lat."','".$lng."',lat,lng)<".$radius;
        $db = getConnection();
        $stmt = $db->query($query);
        $all_cities = $stmt->fetchAll(PDO::FETCH_OBJ);
        //$db = null;
            echo json_encode($all_cities);
        } else{
             $data = array("error"=>true,"message"=>"your key expire please login");
            echo json_encode($data);
        }    
}

function validate_key($key){
    //echo $key;

        $query = "select *from tbl_user where token_expire > now() and token='".$key."'";
        $db = getConnection();
        $stmt = $db->query($query);
        $authenticate = $stmt->fetchAll(PDO::FETCH_OBJ);
        return count($authenticate);

    //echo date("Y-m-d H:i:s", strtotime("+5 minute"));
}


function get_geometry($address){

    //$address = "India+Panchkula";
    $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=India";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response);
    $lat = $response_a->results[0]->geometry->location->lat;
    $long = $response_a->results[0]->geometry->location->lng;
    $return_geometry = array("lat"=>$lat,"lon"=>$long);
    return $return_geometry;
}

function login(){
    
    $expire_duration = date("Y-m-d H:i:s",strtotime("+30 minute"));
    $token = md5(mt_rand());
    $app = new \Slim\Slim();
    $username = $app->request->params('username');
    $password = $app->request->params('password');
    $db = getConnection();
    $sql = "select *from tbl_user where username='".$username."' and `password`=md5('".$password."')";
    $stmt = $db->query($sql);
    $user_data = $stmt->fetchAll(PDO::FETCH_OBJ);
    if(count($user_data)){
        $db->query("update tbl_user set token='".$token."', token_expire='".$expire_duration."' where username='".$username."' and `password`=md5('".$password."')");
        $data = array("error"=>false,"key"=>$token);
        echo json_encode($data);
    } else{
        $data = array("error"=>true,"key"=>"");
        echo json_encode($data);
    }

}

?>