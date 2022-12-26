data "archive_file" "thumbnail_lambda_package" {
  type = "zip"
  #  source_file = "./lambdas/create_thumbnail.py"
  source_dir  = "./lambdas/thumbnails/"
  output_path = "thumbs.zip"
}


resource "aws_lambda_function" "test_lambda_function" {
  function_name    = "create_thumbnails"
  filename         = "thumbs.zip"
  source_code_hash = data.archive_file.thumbnail_lambda_package.output_base64sha256
  role             = aws_iam_role.thumbnail_lambda_role.arn
  runtime          = "python3.7"
  handler          = "create_thumbnail.lambda_handler"
  timeout          = 10
}

resource "aws_s3_bucket_notification" "my-trigger" {
  bucket = "yna-images"

  lambda_function {
    lambda_function_arn = aws_lambda_function.test_lambda_function.arn
    events              = ["s3:ObjectCreated:*"]
  }
}

resource "aws_lambda_permission" "test" {
  statement_id  = "AllowS3Invoke"
  action        = "lambda:InvokeFunction"
  function_name = aws_lambda_function.test_lambda_function.arn
  principal     = "s3.amazonaws.com"
  source_arn    = "arn:aws:s3:::yna-images"
}
