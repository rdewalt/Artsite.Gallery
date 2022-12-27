<pre><?php

require 'vendor/autoload.php';

use Aws\Iam\IamClient;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$s3Client = new S3Client([
    'profile' => 'default',
    'region' => 'us-west-2',
    'version' => '2006-03-01'
]);

$cognito_domain = "https://yna-signup.auth.us-west-2.amazoncognito.com";
$client_id = "5h9g4gpmipec6gmaiqmk0dcso6";
$redirect_uri = "https://yna.solfire.com/cognito.php";
$client_secret = "mcj69iot33q3i2km2cv17ip4mbbsomksnu7s4aa2slt06jgjcp6";

$ch = curl_init();

// Get the token
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
$response = json_decode(curl_exec($ch),true);

// Convert to variables I can use.
$id_token=$response["id_token"];
$access_token=$response["access_token"];
$refresh_token=$response["refresh_token"];
$expires_in=$response["expires_in"];


print_r($response);

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
print "<hr> Okay, this is the logged in part.<br>";
print_r($response);

$C_UID=$response["sub"];
// Set into cookies that expire when we're told they can.
setcookie("I",$id_token, time()+$expires_in,"/","yna.solfire.com",1,1);
setcookie("A",$access_token, time()+$expires_in,"/","yna.solfire.com",1,1);
setcookie("R",$refresh_token, time()+$expires_in,"/","yna.solfire.com",1,1);
setcookie("U",$C_UID, time()+$expires_in,"/","yna.solfire.com",1,1);
 
#create blank S3 buckets for the user's images
print "<hr>" . left($C_UID,2);
$bucket= "yna-images";
$folder= "a/". left($C_UID,2) . "/" . $C_UID . "/";
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
?>