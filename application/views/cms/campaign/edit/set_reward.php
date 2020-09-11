<div>
  <h5 class="title">Set Reward</h5>
</div>
<div>

  <div class="row mb-4 align-items-center form-group">
    <div class="col-md-2">
      <label class="control-label" for="input-setRewardNewUser">New Users * <div id="error"></div></label>
    </div>
    <div class="col-md-3">
      <div class="m-auto">
        <input id="input-setRewardNewUser" type="number" step="0.01" name="a_set_reward[new]" class="form-control text-right" value="<?php echo $set_rewards['new']?>">
      </div>        
    </div>
    <div class="col-md-2">
      <label for="input-setRewardNewUser">%<label>
    </div>
    <div class="col-md-2 align-items-center">
        <?php if(!empty($category_rewards)) echo 'Reccommend % : ' . min(array_column($category_rewards, 'reward')) . ' %' ?>
    </div>
  </div>
  <div class="row mb-4 align-items-center form-group">
    <div class="col-md-2">
      <label class="control-label" for="input-setRewardExistingUser">Existing Users * <div id="error"></div></label>
    </div>
    <div class="col-md-3">
      <div class="m-auto">
        <input id="input-setRewardExistingUser" type="number" step="0.01" name="a_set_reward[existing]" class="form-control text-right" value="<?php echo $set_rewards['existing']?>">
      </div>        
    </div>
    <div class="col-md-2">
      <label for="input-setRewardExistingUser">%<label>
    </div>
    <div class="col-md-2">
        <?php if(!empty($category_rewards)) echo 'Reccommend % : ' . min(array_column($category_rewards, 'reward')) . '%' ?>
    </div>
  </div>

</div>