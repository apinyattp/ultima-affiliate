<div class="card px-5 py-2">
  <div>
    <h4 class="title">Campaign Detail</h4>
    <p class="category"></p>
  </div>
  <div class="card-body">
    <?php echo form_open('cms/campaign/edit/'.$id, ['id' => 'editForm']); ?>
      <input id="campaignId" name="campaignId" value="<?php echo $id ?>" hidden></input>
      <!-- GENERAL INFORMATION SECTION -->
      <div class="row">
        <div class="form-group col-md-6 mb-4">
          <label for="idInput">ID</label>
          <input type="text" class="form-control" id="idInput" placeholder="<?php echo $id;?>" readonly>
        </div>
        <div class="form-group col-md-6 mb-4">
          <label for="input-display_name">Name *</label>
          <input type="text" class="form-control" id="input-display_name" name="display_name" value="<?php echo $display_name;?>">
          <label for="input-display_name"><div id="error"></div></label>
        </div>
        <div class="form-group col-md-6 mb-4">
          <label for="input-startdate">Start Date</label>
          <input type="text" class="form-control" id="input-startdate" placeholder="<?php echo empty($startDate) ? null : date('m/d/Y', strtotime($startDate)); ?>" readonly>
        </div>
        <div class="col-md-6 mb-3">
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label for="input-quicklink">Quick Link</label>
                <input type="text" class="form-control" id="input-quicklink" value="<?php echo $quicklink;?>" readonly>
              </div>
            </div>
            <div class="col-md-1 align-self-end">
              <button type="button" class="btn btn-primary" id="copyQuicklinkBtn"><i class="fas fa-copy"></i></button>
            </div>
          </div>
        </div>
        <div class="form-group col-md-6 mb-4">
          <label for="input-enddate">End Date</label>
          <input type="text" class="form-control" id="input-enddate" placeholder="<?php echo empty($endDate) ? null : date('m/d/Y', strtotime($endDate)); ?>" readonly>
        </div>
        <div class="form-group col-md-6 mb-4">
          <label class="control-label" for="input-cashback">Cashback Duration (Day) * (Recommend : 90-120)</label>
          <input type="text" class="form-control" id="input-cashback" name="cashback" placeholder="" value="<?php echo $cashback;?>">
          <label for="input-cashback"><span id="error"></span></label>
        </div>
        <div class="form-group col-md-6 mb-4">
          <label for="input-defaultdescription">Default Description</label>
          <textarea class="form-control mh-100" id="input-defaultdescription" rows="10" readonly><?php echo $default_description;?></textarea>
        </div>
        <div class="form-group col-md-6 mb-4">
          <label class="control-label" for="input-description">Set New Description *</label>
          <textarea class="form-control mh-100" id="input-description" name="description" rows="10"><?php echo $description;?></textarea>
          <label for="input-description"><span id="error"></span></label>
        </div>
        <div class="form-group col-md-6 mb-4">
          <label for="input-image_file_id">Logo * <div id="error"></div></label> 
          <div class="d-flex justify-content-center">
            <div class="p-2 align-items-center">
              <input id="input-image_file_id" name="image_file_id" value="<?php echo $image_file_id?>" hidden></input>
              <div id="logoDropzone" class="dropzone">
                <div class="dz-message" data-dz-message><span></span></div>
              </div>
              <span id="logo_image_file" hidden><?php echo json_encode($logo_image_file) ?></span>
            </div>
            <div class="p-2 align-self-center">
              <button class="btn btn-primary uploadImageBtn" type="button">Upload</button>
            </div>     
          </div>
        </div>
        <div class="form-group col-md-6 mb-4">
          <label for="exampleFormControlSelect1">Status *</label>
          <select class="form-control" id="input-status" name="status">
            <option <?php if($status == 'active') echo 'selected' ?> value="active">Active</option>
            <option <?php if($status == 'inactive') echo 'selected' ?> value="inactive">Inactive</option>
          </select>
        </div>
      </div>
      
      <!-- DESCRIPTION SECTION -->
      <div class="row mb-4 form-group">
        <div class="col-md-2">
          <label for="input-condition_do">Condition Do * <div id="error"></div></label>
        </div>
        <div class="col-md-10">
          <textarea id="input-condition_do" name="condition_do"><?php echo $condition_do ?></textarea>
        </div>
      </div>
      <div class="row mb-4 form-group">
        <div class="col-md-2">
          <label for="input-condition_dont">Condition Don't * <div id="error"></div></label>
        </div>
        <div class="col-md-10">
          <textarea id="input-condition_dont" name="condition_dont"><?php echo $condition_dont ?></textarea>
        </div>
      </div>
      <div class="row mb-4 form-group">
        <div class="col-md-2">
          <label for="input-note">Note <div id="error"></div></label>
        </div>
        <div class="col-md-10">
          <textarea id="input-note" name="note"><?php echo $note ?></textarea>
        </div>
      </div>
      
      <!-- REWARD SECTION -->
      <?php $this->load->view('cms/campaign/edit/set_reward')?>
      <hr>
      <?php $this->load->view('cms/campaign/edit/default_reward')?>
      <?php $this->load->view('cms/campaign/edit/category_reward')?>
      <hr>
      <?php //$this->load->view('cms/campaign/edit/tier')?>
      <!-- <hr> -->
      <div class="d-flex justify-content-center">
        <button type="button" class="btn btn-success mx-3" id="btn-save">save</button>
        <a href="<?php echo site_url('cms/campaign');?>"><button type="button" class="btn btn-danger mx-3">cancel</button></a>
      </div>
    
    </form>
  </div>
</div>