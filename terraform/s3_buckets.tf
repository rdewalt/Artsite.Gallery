resource "aws_s3_bucket" "images" {
  bucket = "YNA_IMAGES"
}

resource "aws_s3_bucket_policy" "public_read_access_images" {
  bucket = "aws_s3_bucket.image.id"
  policy = <<EOF
{ 
  "Statement": [
    {
      "Sid": "AllowEveryoneReadOnlyAccess",
      "Effect": "Allow",
      "Principal": "*",
      "Action": [ "s3:GetObject"],
      "Resource": [
      "arn:aws:s3:::YNA_IMAGES/*"]
    }
  ]
}
EOF
}

resource "aws_s3_bucket" "image_thumbnails" {
  bucket = "YNA_IMAGES_THUMBNAIL"
}

resource "aws_s3_bucket_policy" "public_read_access_thumbs" {
  bucket = "aws_s3_bucket.image_thumbnails.id"
  policy = <<EOF
{ 
  "Statement": [
    {
      "Sid": "AllowEveryoneReadOnlyAccess",
      "Effect": "Allow",
      "Principal": "*",
      "Action": [ "s3:GetObject"],
      "Resource": [
      "arn:aws:s3:::YNA_IMAGES_THUMBNAIL/*"]
    }
  ]
}
EOF
}