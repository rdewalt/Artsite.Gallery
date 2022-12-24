#Load Balancer Specific code.

# Create a new load balancer
resource "aws_elb" "main-elb" {
  name     = "main-elb"
  internal = "false"
  subnets  = flatten(["${aws_subnet.public.*.id}"])

  listener {
    instance_port     = 80
    instance_protocol = "http"
    lb_port           = 80
    lb_protocol       = "http"
  }

  listener {
    instance_port      = 80
    instance_protocol  = "http"
    lb_port            = 443
    lb_protocol        = "https"
    ssl_certificate_id = "arn:aws:acm:us-west-2:936877644948:certificate/532d60c6-10f5-42e7-84f2-f10e10c6c0a7"
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
  zone_id = "Z10381301HZKPQJ9VVOUJ"
  name    = "yna.solfire.com"
  type    = "A"

  alias {
    name                   = aws_elb.main-elb.dns_name
    zone_id                = aws_elb.main-elb.zone_id
    evaluate_target_health = true
  }
}