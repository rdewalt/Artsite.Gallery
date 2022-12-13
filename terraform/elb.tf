#Load Balancer Specific code.

# Create a new load balancer
resource "aws_elb" "main-elb" {
  name     = "main-elb"
  count    = 1
  internal = "false"

  subnets = flatten(["${aws_subnet.public.*.id}"])

  listener {
    instance_port     = 80
    instance_protocol = "tcp"
    lb_port           = 80
    lb_protocol       = "tcp"
  }

  listener {
    instance_port     = 443
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

  instances                   = flatten(["${aws_instance.applet.*.id}"])
  cross_zone_load_balancing   = true
  idle_timeout                = 400
  connection_draining         = true
  connection_draining_timeout = 400

  tags = {
    Name = "main-elb"
  }
}
