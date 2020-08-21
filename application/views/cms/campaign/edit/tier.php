<div>
  <h5 class="title">Tiers</h5>
  <!-- <p class="category">aa</p> -->
</div>
<div class="px-5 mb-3">

  <?php foreach($a_custom_reward as $type => $custom_rewards) { ?>
    <div class="py-2">
    <h6 class="title mb-3"><?php echo str_replace('_', ' ', $type); ?></h6>
    <?php foreach($custom_rewards as $key => $value) {
          $id = $key . 'Input';
    ?>
      <div class="row align-items-center px-3">
        <div class="col-md-1">
          <label for="<?php echo $id ?>"><?php echo $key ?></label>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <input type="text" id="<?php echo $id ?>" class="form-control" value="<?php echo $value ?>">
          </div>
        </div>
        <div class="col-md-1">
          <label for="<?php echo $id ?>"> % </label>
        </div>
      </div>
    <?php } ?>
    </div>
  <?php } ?>

</div>