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
    region = "us-west-2"
  }
  required_providers {
    ssh = {
      source = "loafoe/ssh"
    }
  }
}

provider "aws" {
  region     = "us-west-2"
  access_key = "AKIA5UISCLSKN2W7BNGX"
  secret_key = "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"
}

#This key is, in this case, stored here in this repo.  NORMALLY this is not done, but since the candidate repo
# I do not have access to environment variables or secrets management of any kind.  This is -intentional- break of
# my normal best practices. 
variable "keyname" {
  default = "rwd-yna" #default key to use.
}


variable "default_ami" {
  description = "Default AMI to use when one isn't specified.  Canonical, Ubuntu, 22.04 LTS, arm64 jammy image build on 2022-12-01"
  default     = "ami-06e2dea2cdda3acda" #ARM
}

variable "connect_user" {
  default = "ubuntu"
}

variable "connect_type" {
  default = "ssh"
}

variable "region_az_count" {
  default = 2
}

data "aws_availability_zones" "available" {
  state = "available"
}

variable "termination_protection" {
  default = "false"
}

variable "webserver_server_count" {
  default = 1
}

variable "database_ec2_size" {
  default = "t4g.medium"
}

variable "webserver_ec2_size" {
  default = "t4g.small"
}

variable "my_home_cidr" {
  default = ["67.182.47.219/32"]
}

variable "dbpass" { # I don't care if you know the DB password, you can't get to it anyway and if you can get to it, I'm fucked anyway.
  description = "The password for the DB master user"
  type        = string
  default     = "86753091024"
}