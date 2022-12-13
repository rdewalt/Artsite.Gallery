# Default variables for this "deployment"
# In prior tf buildouts all environments used the same .tf master file(s) and were only different by the contents of the vars.tf

variable "Environment" {
  description = "This is so we can set up and distinguish all over the place which deployment environment this is."
  default     = "rwd-yna"
}

terraform {
  backend "s3" {
    bucket = "rwd-yna-terraform-bucket"
    key    = "backend/terraform.tfstate"
  }
}

provider "aws" {
  region = "us-west-2"
}

#This key is, in this case, stored here in this repo.  NORMALLY this is not done, but since the candidate repo
# I do not have access to environment variables or secrets management of any kind.  This is -intentional- break of
# my normal best practices. 
variable "keyname" {
  default = "rwd-yna" #default key to use.
}

variable "default_ami" {
  description = "Default AMI to use when one isn't specified.  This is the Amazon Linux 2 64 bit "
  default     = "ami-094125af156557ca2"
}

variable "connect_user" {
  default = "ec2-user" #default for Amazon Linux image above
}

variable "connect_type" {
  default = "ssh"
}

variable "region_az_count" {
  default = 2
}

#Sets up the datasource for use in other locations. 
data "aws_availability_zones" "available" {
  state = "available"
}

#In non-prod this may be set to "false", but just sets the EC2 Termination Protection defaults.
variable "termination_protection" {
  default = "true"
}

# EC2 Webserver counts for this deploymnt;
variable "webserver_server_count" {
  default = 1
}

# EC2 Webserver machine size
variable "database_ec2_size" {
  default = "t4g.medium"
}

# EC2 Webserver machine size
variable "webserver_ec2_size" {
  default = "t4g.small"
}

variable "my_home_cidr" {
  default = ["67.182.47.219/32"]
}