
<div class="row">
  <div class="col-md-12">
    <div class="card px-5 py-2">
      <div>
        <h4 class="title">Report Detail</h4>
        <p class="category"></p>
      </div>
      <div class="card-body">

        <div class="row my-2">
          <div class="col-2">Transaction ID</div>
          <div class="col-4"><?php echo $conversion['conversion_id'] ?></div>
        </div>
        <div class="row my-2">
          <div class="col-2">UID</div>
          <div class="col-4"><?php echo $conversion['uid'] ?></div>
        </div>
        <div class="row my-2">
          <div class="col-2">Campaign Name</div>
          <div class="col-4"><?php echo $conversion['a_campaign']['name'] ?></div>
        </div>
        <div class="row my-2">
          <div class="col-2">Reward</div>
          <div class="col-4"><?php echo number_format($conversion['reward'], 2)?> THB</div>
        </div>
        <div class="row my-2">
          <div class="col-2">Transaction Amount</div>
          <div class="col-4"><?php echo number_format($conversion['transaction_amount'], 2) ?> THB</div>
        </div>
        <div class="row my-2">
          <div class="col-2">Transaction ID</div>
          <div class="col-4"><?php echo $conversion['verification_id'] ?></div>
        </div>
        <div class="row my-2">
          <div class="col-2">User Agent</div>
          <div class="col-4"><?php echo $conversion['user_agent'] ?></div>
        </div>
        <div class="row my-2">
          <div class="col-2">Click Time</div>
          <div class="col-4"><?php echo $conversion['click_time'] ?></div>
        </div>
        <div class="row my-2">
          <div class="col-2">Conversion Time</div>
          <div class="col-4"><?php echo $conversion['conversion_time'] ?></div>
        </div>
        <div class="row my-2">
          <div class="col-2">Confirmation Time</div>
          <div class="col-4"><?php echo ($conversion['confirmation_time'] == '0000-00-00 00:00:00' || empty($conversion['confirmation_time'])) ? '-' : $conversion['confirmation_time'] ?></div>
        </div>
        <div class="row my-2">
          <div class="col-2">Status</div>
          <div class="col-4"><?php echo $conversion['status'] ?></div>
        </div>
        <div class="row my-2">
          <div class="col-2">Datetime Updated</div>
          <div class="col-4"><?php echo $conversion['datetime_updated'] ?></div>
        </div>

      </div>
    </div>
  </div>
</div>