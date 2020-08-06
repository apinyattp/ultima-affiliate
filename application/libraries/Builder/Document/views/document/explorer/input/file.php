<div class="form-group">
    <label><?php echo htmlspecialchars($input->var_name()) ?></label>
    <input type="file" class="input"
        name="<?php echo htmlspecialchars($input->var_name()) ?>"
        data-validate="<?php echo htmlspecialchars($input->validate_string()) ?>">
</div>