<?php
require "library.inc";
require 'vendor/autoload.php';

$cognito_domain = "https://yna-signup.auth.us-west-2.amazoncognito.com";
$client_id = "5h9g4gpmipec6gmaiqmk0dcso6";
$redirect_uri = "https://yna.solfire.com/cognito.php";
$client_secret = "mcj69iot33q3i2km2cv17ip4mbbsomksnu7s4aa2slt06jgjcp6";
$ch = curl_init();
$code = $_GET['code'];

curl_setopt_array($ch, [
    CURLOPT_URL => "$cognito_domain/oauth2/token?" . http_build_query([
        'grant_type'    => "authorization_code",
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'code'          => $code,
        'redirect_uri'  => $redirect_uri
    ]),
    CURLOPT_POSTFIELDS => $params,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-type: application/x-www-form-urlencoded",
        "Authorization: Basic " . base64_encode("$client_id:$client_secret")
    ]
]);

// Convert to variables I can use.
$id_token=$response["id_token"];
$access_token=$response["access_token"];
$refresh_token=$response["refresh_token"];
$expires_in=$response["expires_in"];

curl_setopt_array($ch, [
    CURLOPT_URL => "$cognito_domain/oauth2/userInfo?" . http_build_query([
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'code'          => $code,
        'redirect_uri'  => $redirect_uri
      ]),
    CURLOPT_POSTFIELDS => $params,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-type: application/x-www-form-urlencoded",
        "Authorization: Bearer " . $access_token
    ]
]);

$response = json_decode(curl_exec($ch),true);
$C_UID=$response["sub"];
$C_UN=$response["username"];
$C_Email=$response["email"];

// Set into cookies that expire when we're told they can.

$_SESSION["I"]=$id_token;
$_SESSION["A"]=$access_token;
$_SESSION["R"]=$refresh_token;
$_SESSION["U"]=$C_UID;
$_SESSION["folder"]= $folder;
$_SESSION['loggedin'] = true;

// OKAY, here we do the new user check and setup.
$dbh=getDBH();
$sql = "select * from users where cog_id = :CID";
$sth = $dbh->prepare($sql);
$sth->bindParam(':CID',$C_UID);
$sth->execute();
$foo=$sth->fetchAll();
if ( count($foo)<1 )
    {
    $sql="insert into users values (NULL,:UserName,:Email,'U',:UserID, now() )";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserID', $C_UID);
        $stmt->bindParam(':Email', $C_Email);
        $stmt->bindParam(':UserName', $C_UN);
        $stmt->execute();
    }
    // Create user's S3 bucketry.
    $folder= "a/". left($C_UID,2) . "/" . $C_UID . "/";
    $s3Client = new S3Client([
        'profile' => 'default',
        'region' => 'us-west-2',
        'version' => '2006-03-01'
    ]);
    $bucket= "yna-images";
    $s3Client ->putObject(array(
        'Bucket' => $bucket,
        'Key'    => $folder,
        'Body'   => "",
        'ACL'    => 'public-read'
       ));
    
    $bucket= "yna-images-resized";
    $s3Client ->putObject(array(
        'Bucket' => $bucket,
        'Key'    => $folder,
        'Body'   => "",
        'ACL'    => 'public-read'
       ));
        

}

$sql = "select * from users where cog_id = :CID";
$sth = $dbh->prepare($sql);
$sth->bindParam(':CID',$C_UID);
$sth->execute();
$foo=$sth->fetchAll();
if ( count($foo) )  {
    foreach($foo as $u) {
        $_SESSION['username']=$u['username'];
        $_SESSION['user_id']=$u['id'];
    }
}

    setcookie("U",$C_UID, time()+$expires_in,"/","yna.solfire.com",1,1);

    header ("Location: /");

?>