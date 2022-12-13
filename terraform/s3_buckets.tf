resource "aws_s3_bucket" "images" {
  bucket = "yna-images"
}

resource "aws_s3_bucket_policy" "public_read_access_images" {
  depends_on = [aws_s3_bucket.images]
  bucket     = "yna-images"
  policy     = <<EOF
{ 
  "Statement": [
    {
      "Sid": "AllowEveryoneReadOnlyAccess",
      "Effect": "Allow",
      "Principal": "*",
      "Action": [ "s3:GetObject"],
      "Resource": [
      "arn:aws:s3:::yna-images/*"]
    }
  ]
}
EOF
}

resource "aws_s3_bucket" "image_thumbnails" {
  bucket = "yna-images-thumbnail"
}

resource "aws_s3_bucket_policy" "public_read_access_thumbs" {
  depends_on = [aws_s3_bucket.image_thumbnails]
  bucket     = "yna-images-thumbnail"
  policy     = <<EOF
{ 
  "Statement": [
    {
      "Sid": "AllowEveryoneReadOnlyAccess",
      "Effect": "Allow",
      "Principal": "*",
      "Action": [ "s3:GetObject"],
      "Resource": [
      "arn:aws:s3:::yna-images-thumbnail/*"]
    }
  ]
}
EOF
}