<main>
    <section id="account-forms">
        <div>
            <h3 class="form-title">Login</h3>
            <form action="../../../backend/auth/user_login.php" method="post" id="log-in-form">
                <div class="form-row">
                    <label for="useremail">Email address <span class="required-asteriks">*</span></label>
                    <input type="text" id="useremail" name="useremail" autocomplete="off">
                </div>
                <div class="form-row">
                    <label for="password">Password <span class="required-asteriks">*</span></label>
                    <input type="password" autocomplete="off" id="password" name="userpassword" >
                </div>
                <span class="remember-me-row">
                    <input type="checkbox" id="remember-me" name="remember-me" value="1">
                    <label for="remember-me">Remember me</label>
                </span>
                <button type="submit" id="log-in-button">Log in</button>
                <p class="error-message hidden"></p>

                <a href="../pages/forgot-password.php" class="lost-password-link">Lost your password?</a>
            </form>
        </div>
        <div>
            <h3 class="form-title">Register</h3>
            <form action="../../../backend/auth/register.php" method="post" id="register-form">
                <div class="form-row">
                    <label for="register-username">Username <span class="required-asteriks">*</span></label>
                    <input type="text" id="register-username" name="username" autocomplete="off">
                </div>
                <div class="form-row">
                    <label for="register-email">Email address <span class="required-asteriks">*</span></label>
                    <input type="text" id="register-email" name="email" autocomplete="off" >
                </div>
                <div class="form-row">
                    <label for="register-phone">Phone number</label>
                    <input type="text" id="register-phone" name="phone" autocomplete="off">
                </div>
                <div class="form-row">
                    <label for="register-password">Password <span class="required-asteriks">*</span></label>
                    <input type="password" id="register-password" name="password" autocomplete="off">
                </div>
                
                <p>Your personal data will be used solely to improve your experience throughout this website.</p>
                <button type="submit" id="register-button">Register</button>
                <p class="error-message hidden"> 
                    
                </p>
            </form>
        </div>
    </section>
</main>