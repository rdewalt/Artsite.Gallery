#This code is responsible for setting up the VPC, Subnets,

resource "aws_vpc" "default" {
  cidr_block = "10.0.0.0/16"
  tags = {
    Name = "${var.Environment}"
  }
}

resource "aws_subnet" "public" {
  count                   = var.region_az_count
  vpc_id                  = aws_vpc.default.id
  availability_zone       = data.aws_availability_zones.available.names[count.index % var.region_az_count]
  cidr_block              = "10.0.${count.index}.0/24"
  map_public_ip_on_launch = false

  tags = {
    Set  = "Public"
    Name = "PublicSubnet-${count.index}"
  }
}
