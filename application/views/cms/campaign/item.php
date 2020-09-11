<tr class="<?php if($deleted) echo 'disabled'?>">
  <td scope="row">
  <div class="text-center">
      <img src="<?php echo $image_url ?>" class="rounded">
  </div>
  </td>
  <td><?php echo $name?></td>
  <td>
      <ul>
      <?php foreach($default_reward as $reward) { ?>
          <li>
              <?php $customerType = (isset($reward['customerType'])) ? 'Customer-Type : ' . $reward['customerType'] : ''; ?>
              <?php echo  $customerType . ' ' . $reward['name'].' ('. $reward['reward'] .'%)'?>
          </li>
      <?php } ?>
      </ul>
  </td>
  <td class="text-center"><?php echo $affiliated_date ?></td>
  <td class="text-center">
    <button class="btn <?php echo !($is_highlight) ? 'btn-success' : 'btn-default' ?> btn-round btn-sm" 
            id="pinBtn<?php echo $id ?>" 
            onclick="update_pin(<?php echo $id.','.($is_highlight) ?>)">
            <span><?php echo !($is_highlight) ? 'pin' : 'unpin' ?></span>
    </button>
  </td>
  <td class="text-center">
    <div class="custom-control custom-switch">
      <input type="checkbox" class="custom-control-input" onclick="update_comingsoon_status(<?php echo $id ?>)" id="comingSoonToggle<?php echo $id ?>" name="coming_soon" <?php if($coming_soon) echo 'checked'; ?>>
      <label class="custom-control-label" for="comingSoonToggle<?php echo $id ?>"></label>
    </div>
  </td>
  <td class="text-center">
    <?php echo ($status == 'active') ? 'Active' : 'Inactive' ?>
  </td>
  <td class="text-center">
      <a href="<?php echo site_url('cms/campaign/edit/' . $id); ?>">
        <button class="btn btn-info btn-link">
            <i class="far fa-edit"></i>
        </button>
      </a>
  </td>
  <td class="text-center">
    <button class="btn btn-danger btn-link" type="toggle" onclick="displayDeleteModal(<?php echo $id?>)">
      <i class="fas fa-trash-alt"></i>
    </button>
  </td>
</tr>
