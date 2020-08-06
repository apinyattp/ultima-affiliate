<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo htmlspecialchars($this->doc_head->description()); ?>" />
    <meta name="keywords" content="<?php echo htmlspecialchars($this->doc_head->keyword()); ?>" />

    <base href="<?php echo base_url() ?>"/>

    <title><?php echo htmlspecialchars($this->doc_head->title()) ?></title>

    <?php echo $this->doc_head->css_get() ?>
    <?php echo $this->doc_head->html_get() ?>

    <script>
        var base_url = '<?php echo base_url(); ?>';
    </script>
</head>

<body>