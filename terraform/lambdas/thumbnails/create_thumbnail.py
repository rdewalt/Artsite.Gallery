import boto3
import os
import sys
import uuid
from PIL import Image
import PIL.Image
     
s3_down = boto3.client('s3')
     
def resize_image(image_path, resized_path):
    with Image.open(image_path) as image:
        image.thumbnail((150, 150))
        image.save(resized_path)
     
def lambda_handler(event, context):
    for record in event['Records']:
        bucket = record['s3']['bucket']['name']
        key = record['s3']['object']['key'] 
        infile=key.split("/").pop()
        ext=key.split(".").pop()
        download_path = '/tmp/{}.{}'.format(uuid.uuid4(),ext)
        upload_path = '/tmp/resized-{}'.format(infile)
        if not os.path.exists('/tmp/'):
            os.makedirs('/tmp/')
        s3_down.download_file(bucket, key, download_path)
        resize_image(download_path, upload_path)
        s3_down.upload_file(upload_path, '{}-resized'.format(bucket), key)