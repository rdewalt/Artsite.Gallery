resource "aws_s3_bucket" "images" {
  bucket = "yna-images"
}

resource "aws_s3_bucket_policy" "public_read_access_images" {
  depends_on = [aws_s3_bucket.images]
  bucket     = "yna-images"
  policy     = <<EOF
{ 
  "Version" : "2012-10-17",
  "Statement": [
    {
      "Sid": "AllowEveryoneReadOnlyAccess",
      "Effect": "Allow",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": "arn:aws:s3:::yna-images/*"
    }
  ]
}
EOF
}

resource "aws_s3_bucket" "image_thumbnails" {
  bucket = "yna-images-resized"
}

resource "aws_s3_bucket_policy" "public_read_access_thumbs" {
  depends_on = [aws_s3_bucket.image_thumbnails]
  bucket     = "yna-images-resized"
  policy     = <<EOF
{ 
  "Version" : "2012-10-17",
  "Statement": [
    {
      "Sid": "AllowEveryoneReadOnlyAccess",
      "Effect": "Allow",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": 
      "arn:aws:s3:::yna-images-resized/*"
    }
  ]
}
EOF
}