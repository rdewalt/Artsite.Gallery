#This code is responsible for setting up the VPC, Subnets,

resource "aws_vpc" "default" {
  cidr_block = "10.1.0.0/16"
  tags = {
    Name = "${var.Environment}"
  }
}

resource "aws_subnet" "public" {
  #  count                   = var.region_az_count
  vpc_id = aws_vpc.default.id
  #  availability_zone       = data.aws_availability_zones.available.names[count.index % var.region_az_count]
  availability_zone       = data.aws_availability_zones.available.names[0]
  cidr_block              = "10.1.0.0/24"
  map_public_ip_on_launch = false
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
  subnet_id      = aws_subnet.public.id
}


resource "aws_subnet" "private" {
  #  count                   = var.region_az_count
  vpc_id = aws_vpc.default.id
  #  availability_zone       = data.aws_availability_zones.available.names[count.index % var.region_az_count]
  availability_zone       = data.aws_availability_zones.available.names[0]
  cidr_block              = "10.1.1.0/24"
  map_public_ip_on_launch = false
}

resource "aws_eip" "basic_eip" {
  vpc = true
}

resource "aws_nat_gateway" "nat_gateway" {
  allocation_id = aws_eip.basic_eip.id
  subnet_id     = aws_subnet.public.id
}

resource "aws_route_table" "Nat_Gateway" {
  vpc_id = aws_vpc.default.id

  route {
    cidr_block     = "0.0.0.0/0"
    nat_gateway_id = aws_nat_gateway.nat_gateway.id
  }
}

resource "aws_main_route_table_association" "main_table" {
  vpc_id         = aws_vpc.default.id
  route_table_id = aws_route_table.Nat_Gateway.id
}


resource "aws_route_table_association" "internal_rtb_assoc1" {
  route_table_id = aws_route_table.Nat_Gateway.id
  subnet_id      = aws_subnet.private.id
}