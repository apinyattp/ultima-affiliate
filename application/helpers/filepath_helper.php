<?php
function mkpath($upload_path) {
    $a_upload_path = explode(DIRECTORY_SEPARATOR, $upload_path);
    $path = upload_file_path();
    foreach ($a_upload_path as $folder_name) {
        $path .= $folder_name . DIRECTORY_SEPARATOR;
        if(!is_dir($path)) mkdir($path, 0777, TRUE);

        if(!is_writable($path)) chmod($path, 0777);
    }
}