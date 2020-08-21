<div class="card px-5 py-2">
  <div>
    <h4 class="title">Campaign Detail</h4>
    <p class="category"></p>
  </div>
  <div class="card-body">
    <form action="">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="idInput">ID</label>
            <input type="text" class="form-control" id="idInput" placeholder="<?php echo $campaign['id'];?>" readonly>
          </div>
          <div class="form-group">
            <label for="startDateInput">Start Date</label>
            <input type="text" class="form-control" id="startDateInput" placeholder="<?php echo date('m/d/Y', strtotime($campaign['startDate'])); ?>" readonly>
          </div>
          <div class="form-group">
            <label for="endtDateInput">End Date</label>
            <input type="text" class="form-control" id="endtDateInput" placeholder="<?php echo date('m/d/Y', strtotime($campaign['endDate'])); ?>" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="nameInput">Name</label>
            <input type="text" class="form-control" id="nameInput" value="<?php echo $campaign['name'];?>">
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label for="quickLinkInput">Quick Link</label>
                <input type="text" class="form-control" id="quickLinkInput" value="<?php echo $campaign['quicklink'];?>" readonly>
              </div>
            </div>
            <div class="col-md-1 align-self-end">
              <button type="button" class="btn btn-primary"><i class="fas fa-copy"></i></button>
            </div>
          </div>
          <div class="form-group">
            <label for="cashbackInput">Cashback Duration (Day)</label>
            <input type="text" class="form-control" id="cashbackInput" placeholder="" value="<?php echo $campaign['redeemable_in'];?>">
          </div>
        </div>
      </div>
      
      <!-- <hr> -->

      <div class="row mt-4 mb-4">
        <div class="col-md-2">
          <label for="descriptionInput">Description</label>
        </div>
        <div class="col-md-10">
          <div class="form-group">
            <textarea class="form-control mh-100" id="descriptionInput" rows="10" readonly><?php echo $campaign['description'];?></textarea>
          </div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-md-2">
          <label for="doEditor">Condition Do</label>
        </div>
        <div class="col-md-10">
          <div id="doEditor"></div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-md-2">
          <label for="dontEditor">Condition Don't</label>
        </div>
        <div class="col-md-10">
          <div id="dontEditor"></div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-md-2">
          <label for="noteEditor">Note</label>
        </div>
        <div class="col-md-10">
          <div id="noteEditor"></div>
        </div>
      </div>
      
      <hr>
      <?php $this->load->view('cms/campaign/edit/default_reward')?>
      <?php $this->load->view('cms/campaign/edit/category_reward')?>
      <hr>
      <?php $this->load->view('cms/campaign/edit/tier')?>
      <hr>
      <div class="d-flex justify-content-center">
        <button type="button" class="btn btn-success mx-3">save</button>
        <button type="button" class="btn btn-danger mx-3">cancel</button>
      </div>
    
    </form>
  </div>
</div>