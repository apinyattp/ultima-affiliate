<div>
  <h5 class="title">Category Reward</h5>
</div>
<div class="px-5 mb-3">
  <div class="table-responsive table-mh-5">
    <table class="table">
      <colgroup>
        <col span="1" class="w-25">
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
      <tbody class="overflow-auto">
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
                <?php 
                  if(!empty($category_reward['text'])) {
                    $reward = str_replace('%', '', $category_reward['text']);
                  }else {
                    $reward = $category_reward['reward'];
                  }
                ?>
                <input type="text" class="form-control text-right" value="<?php echo $reward ?>" readonly>
              </div>
            </td>
            <td>
              <?php echo ($category_reward['type'] == 'CPA_FIXED') ? 'บาท' : '%' ?>
            </td>
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
</div>