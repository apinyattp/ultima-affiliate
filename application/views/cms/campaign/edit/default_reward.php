<div>
  <h5 class="title">Default Reward</h5>
  <p class="category">aa</p>
</div>
<div class="px-5 mb-3">
  <table class="table">
    <colgroup>
      <col span="1" class="w-75">
      <col span="1" class="w-25">
      <col span="1" class="w-25">
    </colgroup>
    <thead>
      <th>Name</th>
      <th>Reward</th>
      <th></th>
    </thead>
    <tbody>
      <?php foreach($default_rewards as $default_reward) { ?>
        <tr>
          <td>
            <div class="form-group">
              <input type="text" class="form-control" value="<?php echo $default_reward['name']?>" readonly>
            </div>
          </td>
          <td>
            <div class="form-group">
              <input type="text" class="form-control text-right" value="<?php echo $default_reward['reward']?>" readonly>
            </div>
          </td>
          <td>
            <?php echo ($default_reward['type'] == 'CPA_FIXED') ? 'บาท' : '%' ?>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>