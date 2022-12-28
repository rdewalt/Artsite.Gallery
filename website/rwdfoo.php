<?php
$my_uid="50543915-1090-41e1-95cc-b55d3cf0b4b2";
$user_pool_id="us-west-2_tfaLXU1i7";

require 'vendor/autoload.php';

use Aws\Iam\IamClient;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

$client = new CognitoIdentityProviderClient([
    'profile' => 'default',
    'region' => 'us-west-2',
    'version' => '2016-04-18'
]);

$result = $client->listUserPools([
    'MaxResults' => 10
]);

print "<pre>";
print_r($_SESSION);
print "<hr>";
print_r($result);
/*
$s3Client = new S3Client([
    'profile' => 'default',
    'region' => 'us-west-2',
    'version' => '2006-03-01'
]);

$buckets = $s3Client->listBuckets();
foreach ($buckets['Buckets'] as $bucket) {
    echo $bucket['Name'] . "\n";
}
*/


?>