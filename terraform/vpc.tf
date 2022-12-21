#This code is responsible for setting up the VPC, Subnets,

resource "aws_vpc" "default" {
  cidr_block = "10.1.0.0/16"
  tags = {
    Name = "${var.Environment}"
  }
}

resource "aws_subnet" "public" {
  count                   = var.region_az_count
  vpc_id                  = aws_vpc.default.id
  availability_zone       = data.aws_availability_zones.available.names[count.index % var.region_az_count]
  cidr_block              = "10.1.${count.index}.0/24"
  map_public_ip_on_launch = false

  tags = {
    Set  = "Public"
    Name = "PublicSubnet-${count.index}"
  }
}

resource "aws_internet_gateway" "default" {
  vpc_id = aws_vpc.default.id
}

resource "aws_route_table" "igw_table" {
  vpc_id = aws_vpc.default.id
  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.default.id
  }
}

resource "aws_route_table_association" "main_rtb_assoc1" {
  route_table_id = aws_route_table.igw_table.id
  subnet_id      = aws_subnet.public.0.id
}

resource "aws_route_table_association" "main_rtb_assoc2" {
  route_table_id = aws_route_table.igw_table.id
  subnet_id      = aws_subnet.public.1.id
}