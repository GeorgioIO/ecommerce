<h3 class="section-title">Lost your password ?</h3>

<form action="../../../backend/auth/lost_password.php" method="POST" id="lost-password-form">
    <p>Please enter your email , you will receive a email to create a new password.</p>
    <div class="form-row">
        <label for="lost-pass-email">Email <span class="required-asteriks">*</span> </label>
        <input type="text" id="lost-pass-email" name="useremail" autocomplete="off">
    </div>
    <button type="submit" id="reset-email-button">Reset password</button>
</form>