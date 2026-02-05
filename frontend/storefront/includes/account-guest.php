<main>
    <section id="account-forms">
        <div>
            <h3 class="form-title">Login</h3>
            <form id="log-in-form">
                <div class="form-row">
                    <label for="username-email">Username or email address <span class="required-asteriks">*</span></label>
                    <input type="text" id="username-email" name="username-email" autocomplete="off" required>
                </div>
                <div class="form-row">
                    <label for="password">Password <span class="required-asteriks">*</span></label>
                    <input type="password" autocomplete="off" id="password" name="userpassword" required>
                </div>
                <span class="remember-me-row">
                    <input type="checkbox" id="remember-pass">
                    <label for="remember-pass">Remember me</label>
                </span>

                <button type="button" id="log-in-button">Log in</button>
                <a href="" class="lost-password-link">Lost your password?</a>
            </form>
        </div>
        <div>
            <h3 class="form-title">Register</h3>
            <form action="" id="register-form">
                <div class="form-row">
                    <label for="register-username">Username <span class="required-asteriks">*</span></label>
                    <input type="text" id="register-username" name="username" autocomplete="off" required>
                </div>
                <div class="form-row">
                    <label for="register-email">Email address <span class="required-asteriks">*</span></label>
                    <input type="text" id="register-email" name="email" autocomplete="off" required>
                </div>
                <div class="form-row">
                    <label for="register-password">Password <span class="required-asteriks">*</span></label>
                    <input type="text" id="register-password" name="password" autocomplete="off" required>
                </div>
                <p>Your personal data will be used solely to improve your experience throughout this website.</p>
                <button type="button" id="register-button">Register</button>
            </form>
        </div>
    </section>
</main>