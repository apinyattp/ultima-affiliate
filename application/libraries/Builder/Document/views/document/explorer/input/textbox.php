<div class="form-group" data-name="<?php echo htmlspecialchars($input->var_name()) ?>" data-type="textbox" data-scope="body">
    <label>
        <?php if(!$input->is_required()){ ?>
            <input type="checkbox" class="is_not_null">
        <?php } ?>
        <?php echo htmlspecialchars($input->var_name()) ?>
    </label>
    <input type="text" class="form-control input<?php echo $input->is_required() ? '' : ' not_required'?>"
        name="<?php echo htmlspecialchars($input->var_name()) ?>"
        data-validate="<?php echo htmlspecialchars($input->validate_string()) ?>">
</div>