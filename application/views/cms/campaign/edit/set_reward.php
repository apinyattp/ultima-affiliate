<div>
  <h5 class="title">Set Reward</h5>
</div>
<div>

  <div class="row mb-4 align-items-center">
        <div class="col-md-2">
          <label for="inputSetRewardNewUser">New Users *</label>
        </div>
        <div class="col-md-3">
            <div class="form-group m-auto">
              <input id="inputSetRewardNewUser" type="number" step="0.01" name="a_set_reward[new]" class="form-control text-right" value="<?php echo $set_rewards['new']?>">
            </div>        
        </div>
        <div class="col-md-2">
          <label for="dontEditor">%<label>
        </div>
    </div>
    <div class="row mb-4 align-items-center">
        <div class="col-md-2">
          <label for="inputSetRewardExistingUser">Existing Users *</label>
        </div>
        <div class="col-md-3">
            <div class="form-group m-auto">
              <input id="inputSetRewardExistingUser" type="number" step="0.01" name="a_set_reward[existing] ?>]" class="form-control text-right" value="<?php echo $set_rewards['existing']?>">
            </div>        
        </div>
        <div class="col-md-2">
          <label for="dontEditor">%<label>
        </div>
    </div>

</div>