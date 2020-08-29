<tr>
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
      <input type="checkbox" class="custom-control-input" onclick="update_status(<?php echo $id ?>)" id="statusToggle<?php echo $id ?>" name="status" <?php if($status == 'active') echo 'checked'; ?>>
      <label class="custom-control-label" for="statusToggle<?php echo $id ?>"></label>
      </div>
  </td>
  <td class="text-center">
      <form action="<?php echo site_url('cms/campaign/edit/' . $id); ?>">
        <button class="btn btn-info btn-link">
            <i class="far fa-edit"></i>
        </button>
      </form>
  </td>
  <td class="text-center">
    <button class="btn btn-danger btn-link" type="toggle" onclick="displayDeleteModal(<?php echo $id?>)">
      <i class="fas fa-trash-alt"></i>
    </button>
  </td>
</tr>
