data "archive_file" "thumbnail_lambda_package" {
  type = "zip"
  #  source_file = "./lambdas/create_thumbnail.py"
  source_dir  = "./lambdas/thumbnails/"
  output_path = "thumbs.zip"
}

data "archive_file" "hourly_lambda_package" {
  type = "zip"
  #  source_file = "./lambdas/create_thumbnail.py"
  source_dir  = "./lambdas/hourly_runner/"
  output_path = "hourly.zip"
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

resource "aws_lambda_function" "hourly_lambda_function" {
  function_name    = "hourly_maintenance"
  filename         = "hourly.zip"
  source_code_hash = data.archive_file.hourly_lambda_package.output_base64sha256
  role             = aws_iam_role.thumbnail_lambda_role.arn #overkill, since it contains S3, but that may be important in the future.
  runtime          = "python3.9"
  handler          = "hourly_runner.lambda_handler"
  timeout          = 10
}

resource "aws_cloudwatch_event_rule" "every_hour" {
  name                = "every-hour"
  schedule_expression = "rate(1 hour)"
}


resource "aws_cloudwatch_event_target" "trigger_hourly_event" {
  rule      = aws_cloudwatch_event_rule.every_hour.name
  target_id = "hourly_lambda_function"
  arn       = aws_lambda_function.hourly_lambda_function.arn
}

resource "aws_lambda_permission" "allow_cloudwatch_to_call_hourly" {
  statement_id  = "AllowExecutionFromCloudWatch"
  action        = "lambda:InvokeFunction"
  function_name = aws_lambda_function.hourly_lambda_function.function_name
  principal     = "events.amazonaws.com"
  source_arn    = aws_cloudwatch_event_rule.every_hour.arn
}