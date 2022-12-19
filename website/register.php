<?php
include_once 'header.inc';
include_once 'register.inc.php';

// The SHA512 .js file will hash the password using SHA512.
//  we never /EVER/ send the password in the clear, even over SSL.
//  This way what the site gets is a hash, not a password as well.
?>
        <title>Yart Me!: Registration Form</title>
        <script type="text/JavaScript" src="js/sha512.js"></script>
        <script type="text/JavaScript" src="js/forms.js"></script>
    <body>
    <br clear="all">
    <div class="registerBox">
        <h1>Register!</h1>
        <?php if (!empty($error_msg)) { echo $error_msg; } ?>
        <ul>
            <li>Usernames may contain only digits, upper and lowercase letters and underscores</li>
            <li>Emails must have a valid email format</li>
            <li>Passwords must be at least 6 characters long</li>
            <li>Passwords must contain
                <ul>
                    <li>At least one uppercase letter (A..Z)</li>
                    <li>At least one lowercase letter (a..z)</li>
                    <li>At least one number (0..9)</li>
                </ul>
            </li>
            <li>Your password and confirmation must match exactly of course.</li>
            <li>Your <b><i>Display Name</i></b> is what others will see, but you will login with your email.</li>
            <li>Your Email will never be shown to anyone.</li>
        </ul>
        <br><br>
        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>"
                method="post"
                name="registration_form">
            <b>Display Name:</b> <input type='text' name='username' id='username' /><br>
            <b>Email:</b> <input type="text" name="email" id="email" /><br>
            <b>Password:</b> <input type="password" name="password" id="password"/><br>
            <b>Confirm password:</b> <input type="password" name="confirmpwd" id="confirmpwd"/><br>
            <input type="button" value="Register" onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.confirmpwd);" />
        </form>
    </div>
        <p>Return to the <a href="index.php">login page</a>.</p>
    </body>
</html>