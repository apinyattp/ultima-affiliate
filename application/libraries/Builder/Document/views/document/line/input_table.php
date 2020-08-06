<?php
if($table === FALSE){
    ?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Parameter Name</th>
                <th>Type</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
    <?php
}

$a_validate = explode('|', $line['data']['validate']);

$required = '';
if(($pos = array_search('required', $a_validate)) !== FALSE){
    $required = '<span class="text-danger">*</span>';
    array_splice($a_validate, $pos, 1);
}

$a_remove = ['trim'];
foreach($a_remove as $remove){
    if(($pos = array_search($remove, $a_validate)) !== FALSE){
        array_splice($a_validate, $pos, 1);
    }
}

$a_validate = array_map(
    function($validate){
        if(!preg_match("/^(.+?)(\.(.+)|\((.+)\))?$/", $validate, $match)) return $validate;
        $data_type = $match[1];
        switch($data_type){
            case 'string':
            case 'int':
            case 'float':
            case 'boolean':
            case 'array':
            case 'object':
            case 'json':
            case 'email':
                return ucfirst($data_type);
            case 'tel':
                return 'Telephone';
            case 'tel_mobile':
                return 'Telephone/Mobile';
            case 'mobile':
                return 'Mobile';
            case 'datetime_without_sec':
                return 'Datetime w/o sec';
            case 'enum':
                return str_replace(';', ', ', $validate);

        }
        return $validate;
    },
    $a_validate
);

?>
<tr>
    <td><?php echo htmlspecialchars($line['value']).$required ?></td>
    <td><?php echo htmlspecialchars(implode(',', $a_validate)) ?></td>
    <td><?php echo htmlspecialchars($line['data']['description']) ?></td>
</tr>
