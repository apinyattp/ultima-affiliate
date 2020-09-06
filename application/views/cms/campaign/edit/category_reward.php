<div>
  <h5 class="title">Category Reward</h5>
  <p class="category">aa</p>
</div>
<div class="px-5 mb-3">
  <table class="table w-100">
    <colgroup>
       <col span="1" class="w-50">
       <col span="1" class="w-50">
       <col span="1" class="w-auto">
       <col span="1" class="w-auto">
       <col span="1" class="w-auto">
       <col span="1" class="w-auto">
    </colgroup>
    <thead>
      <th>ID</th>
      <th>Name</th>
      <th>Reward</th>
      <th></th>
      <!-- <th>Custom Reward</th>
      <th></th> -->
    </thead>
    <tbody>
      <?php foreach($category_rewards as $key => $category_reward) { ?>
        <tr>
          <td>
            <div class="form-group">
              <input type="text" class="form-control" value="<?php echo $category_reward['category_id']?>" readonly>
            </div>
          </td>
          <td>
            <div class="form-group">
              <input type="text" class="form-control" value="<?php echo $category_reward['name']?>" readonly>
            </div>
          </td>
          <td>
            <div class="form-group">
              <input type="text" class="form-control text-right" value="<?php echo $category_reward['reward']?>" readonly>
            </div>
          </td>
          <td><?php echo ($category_reward['type'] == 'CPA_FIXED') ? 'บาท' : '%' ?></td>
          <!-- <td>
            <div class="form-group">
              <input type="text" class="form-control text-right" id="categoryRewardInput<?php echo $category_reward['id'] ?>" name="category_rewards[<?php echo $category_reward['id'] ?>][custom_reward]" value="<?php echo $category_reward['custom_reward']?>">
            </div>
          </td>
          <td>%</td> -->
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>