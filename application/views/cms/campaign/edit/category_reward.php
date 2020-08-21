<div>
  <h5 class="title">Category Reward</h5>
  <p class="category">aa</p>
</div>
<div class="px-5 mb-3">
  <table class="table w-100">
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
      <?php foreach($category_rewards as $category_reward) { ?>
        <tr>
          <td>
            <div class="form-group">
              <input type="text" class="form-control" value="<?php echo $category_reward['name']?>" readonly>
            </div>
          </td>
          <td>
            <div class="form-group">
              <input type="text" class="form-control" value="<?php echo $category_reward['reward']?>" readonly>
            </div>
          </td>
          <td>%</td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>