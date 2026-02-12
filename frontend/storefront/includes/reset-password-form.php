<form method="POST"  id="reset-password-form">
    <input type="hidden" name="token" value="<?= $token ?>" >
    <div class="form-row">
        <label for="new-password">New password</label>
        <input type="password" id="new-password" name="new-password">
    </div>
    <div class="form-row">
        <label for="confirm-password">Confirm password</label>
        <input type="password" id="confirm-password" name="confirm-password">
    </div>
    <button id="submit-pass-reset-button">Reset Password</button>
</form>