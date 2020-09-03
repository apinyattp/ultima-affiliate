
<div class="row">
  <div class="col-md-12">
    <div class="card px-5 py-2">
      <div>
        <h4 class="title">Conversion Report</h4>
        <p class="category"></p>
      </div>
      <?php $this->load->view('cms/report/search_filter'); ?>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr class="text-nowrap">
              <th scope="col">Conversion ID</th>
              <!-- <th scope="col">Transaction ID</th> -->
              <th scope="col">Campaign</th>
              <th scope="col">UID</th>
              <th scope="col">Cashback</th>
              <th scope="col">Request Time</th>
              <th scope="col">Click Time</th>
              <th scope="col">Confirmation Time</th>
              <th scope="col">Updated Time</th>
              <th scope="col">Status</th>
              <th class="text-center" scope="col">Detail</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($a_conversion['lists'] as $conversion) { ?>
              <tr>
                <td><?php echo $conversion['conversion_id'] ?></td>
                <!-- <td><?php echo $conversion['verification_id'] ?></td> -->
                <td><?php echo $conversion['a_campaign']['name'] ?></td>
                <td><?php echo $conversion['uid'] ?></td>
                <td class="text-right"><?php echo $conversion['reward'] ?></td>
                <td><?php echo $conversion['click_time'] ?></td>
                <td><?php echo $conversion['conversion_time'] ?></td>
                <td><?php echo $conversion['confirmation_time'] ?></td>
                <td><?php echo $conversion['datetime_updated'] ?></td>
                <td><?php echo $conversion['status'] ?></td>
                <td class="text-center">
                  <button type="button" class="btn btn-info btn-sm">
                    <i class="far fa-eye"></i>
                  </button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
  
        <?php
          $a_conversion['pagination']['path'] = 'cms/report/list';
          $this->load->view('cms/template/pagination', $a_conversion['pagination']) 
        ?>

      </div>
    </div>
  </div>
</div>