#Load Balancer Specific code.

# Create a new load balancer
resource "aws_elb" "main-elb" {
  name     = "main-elb"
  internal = "false"

  subnets = flatten(["${aws_subnet.public.*.id}"])

  listener {
    instance_port     = 80
    instance_protocol = "tcp"
    lb_port           = 80
    lb_protocol       = "tcp"
  }

  listener {
    instance_port     = 80
    instance_protocol = "tcp"
    lb_port           = 443
    lb_protocol       = "tcp"
  }

  health_check {
    healthy_threshold   = 2
    unhealthy_threshold = 2
    timeout             = 5
    target              = "HTTP:80/"
    interval            = 30
  }

  security_groups = ["${aws_security_group.elb.id}"]

  instances                   = flatten(["${aws_instance.webserver.*.id}"])
  cross_zone_load_balancing   = true
  idle_timeout                = 400
  connection_draining         = true
  connection_draining_timeout = 400

  tags = {
    Name = "main-elb"
  }
}

resource "aws_route53_record" "yna-elb" {
  zone_id = aws_route53_zone.primary.zone_id
  name    = "yna.solfire.com"
  type    = "A"

  alias {
    name                   = aws_elb.main-elb.dns_name
    zone_id                = aws_elb.main-elb.zone_id
    evaluate_target_health = true
  }
}