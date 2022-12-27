<?php

require 'vendor/autoload.php';

use Aws\Iam\IamClient;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$s3Client = new S3Client([
    'profile' => 'default',
    'region' => 'us-west-2',
    'version' => '2006-03-01'
]);

$buckets = $s3Client->listBuckets();
foreach ($buckets['Buckets'] as $bucket) {
    echo $bucket['Name'] . "\n";
}

?>