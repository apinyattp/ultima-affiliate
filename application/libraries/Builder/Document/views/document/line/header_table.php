<?php
if($table === FALSE){
    ?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Header</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
    <?php
}
?>
<tr>
    <td><?php echo htmlspecialchars($line['value']) ?></td>
    <td><?php echo htmlspecialchars($line['data']['description']) ?></td>
</tr>
