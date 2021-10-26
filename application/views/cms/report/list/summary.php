<div class="d-flex justify-content-center">
    <div class="card px-2 mx-auto">
        <div class="card-body">
            <h6 class="card-title">total order</h6>
            <h5 class="card-text"><?php echo $a_conversion['pagination']['total_items'] ?></h5>
            <small>Transaction Amount : <?php echo number_format($a_summary['transaction_amount'], 2)?> THB</small>
        </div>
    </div>
    <div class="card px-2 mx-auto">
        <div class="card-body">
            <h6 class="card-title">pending reward</h6>
            <h5 class="card-text"><?php echo number_format($a_summary['reward_pending'], 2)?> THB</h5>
            <small>Transaction Amount : <?php echo number_format($a_summary['transaction_amount_pending'], 2)?> THB</small>
        </div>
    </div>
    <div class="card px-2 mx-auto">
        <div class="card-body">
            <h6 class="card-title">approved reward</h6>
            <h5 class="card-text"><?php echo number_format($a_summary['reward_approved'], 2)?> THB</h5>
            <small>Transaction Amount : <?php echo number_format($a_summary['transaction_amount_approved'], 2)?> THB</small>
        </div>
    </div>
    <div class="card px-2 mx-auto">
        <div class="card-body">
            <h6 class="card-title">rejected reward</h6>
            <h5 class="card-text"><?php echo number_format($a_summary['reward_rejected'], 2)?> THB</h5>
            <small>Transaction Amount : <?php echo number_format($a_summary['transaction_amount_rejected'], 2)?> THB</small>
        </div>
    </div>
    <div class="card px-2 mx-auto">
        <div class="card-body">
            <h6 class="card-title">total reward</h6>
            <h5 class="card-text"><?php echo number_format($a_summary['reward'], 2)?> THB</h5>
            <small>Transaction Amount : <?php echo number_format($a_summary['transaction_amount'], 2)?> THB</small>
        </div>
    </div>
    <div class="card px-2 mx-auto" style="width: 200px;">
        <div class="card-body">
            <h6 class="card-title">missing total</h6>
            <h5 class="card-text"><?php echo number_format($a_summary['missing_total']) ?></h5>
            <small></small>
        </div>
    </div>
</div>
