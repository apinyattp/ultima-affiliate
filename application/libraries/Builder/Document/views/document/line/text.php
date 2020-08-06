<?php
preg_match("/^(#*)(.*)$/", $line['value'], $match);
$tag = !empty($match[1]) ? 'h'.strlen($match[1]) : 'p';
echo "<$tag>".htmlspecialchars($match[2])."</$tag>";
