<?php

include 'tga.php';

$im = imagecreatefromtga('image.tga');
imagepng($im, 'test.png');

list($width, $height, $type, $bits, $type) = getimagesizetga('image.tga');
echo 'Width: ' . $width . ' Height: '. $height . '<br>';
