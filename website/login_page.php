<?php
require_once 'header.inc';
include_once 'library.inc';

if (login_check() == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>
<script>
function grabOTP()
{
    myEmail=document.getElementById("email").value;
    xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","otpcheck.php?e="+myEmail,true);
    xmlhttp.send();
        xmlhttp.onreadystatechange=function(){
         if (xmlhttp.readyState==4){
          if (xmlhttp.status==200 || window.location.href.indexOf("http")==-1){
                data=JSON.parse(xmlhttp.responseText);
                        if (data.email==myEmail) {
                            document.getElementById("OTPBox").style.display="block";
                        }
          }
      }
  }
}
</script>
<br clear="left">
<br>
<hr>
<br>
        <script type="text/JavaScript" src="js/sha512.js"></script>
        <script type="text/JavaScript" src="js/forms.js"></script>
        <?php
        if (isset($_GET['error'])) { // Generic error message, do not reveal WHY we failed out.
            print '<p class="error">An Error Has Occured During Logging In, Check your E-mail/Passwords/2FA (if enabled), Try Again.</p>';
            print '<p class="error"> If you have lost your password, <a href="resetpass.php">[Click here] to send a reset email to reset your password.</a></p>';
            print '<p class="error">If you are having problems with the Two Factor Authentication/OTP system, you will need to <a href="mailto:rdewalt@gmail.com?subject=OTP_Problems">[Mail the Admin]</a> to solve it.</p>';
        }
        ?>
        <div style="margin:10px;padding:10px;">
        <form action="login_processor.php" method="post" name="login_form">
            Email: <input type="text" name="email" id="email" onchange="grabOTP();" />
            <br>Password: <input type="password"
                             name="password"
                             id="password"/>
            <br>Remember Me: <input type="checkbox"
                             name="RememberMe"
                             id="checkbox" value="Y"/> (Remember My Login. Do not use on a public machine)
            <div id="OTPBox" class="OTPLoginBox">Authentication: <input type="password" name="OTP" id="OTPentry"><br>Password from Authenticator App.</div>
            <br>
            <input type="button"
                   value="Login"
                   onclick="formhash(this.form, this.form.password);" />
        </form>
        </div>
<?php
        if (login_check() == true) {
                        echo '<p>Currently logged ' . $logged . ' as ' . htmlentities($_SESSION['username']) . '.</p>';

            echo '<p>Do you want to change user? <a href="logout.php">Log out</a>.</p>';
        } else {
                        echo '<p>Currently logged ' . $logged . '.</p>';
                        echo "<p>If you don't have a login, please <a href='register.php'>register</a></p>";
                }

require_once 'footer.inc';
?>