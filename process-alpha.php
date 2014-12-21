<?php

$image = new Imagick("images/alpha2.png");

$width = $image->getImageWidth();
$height = $image->getImageHeight();

$alphabet = " ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$alphaN = 0;
$alphaX = array();
$alphaW = array();
$last_detected = false;

for ($x = 0 ; $x < $width ; $x++) {
  $detected = false;

  //print sprintf("%04d: ", $x);

  for ($y = 0 ; $y < $height ; $y++) {
    $pixel = $image->getImagePixelColor($x, $y);
    $colors = $pixel->getColor();

    //print "$x,$y: " . json_encode($colors) . "\n";

    if ($colors['a'] != 0 && ($colors['r'] != 255 || $colors['g'] != 255 || $colors['b'] != 255)) {
      $detected = true;
    }

    //print sprintf("%x%x%x ", $colors['r'], $colors['g'], $colors['b']);
    //print ($detected ? "X" : " ");
  }

  //print "\n";

  //usleep(1000000 / 4);

  if ($detected) {
    if (!$last_detected) {
      $alphaN++;
      $alphaX[$alphabet[$alphaN]] = $x;
    }
  } else {
    if ($last_detected) {
      $alphaW[$alphabet[$alphaN]] = $x - $alphaX[$alphabet[$alphaN]] + 1;
    }
  }

  $last_detected = $detected;

  //break;
}

print "\$alphaX = " . var_export($alphaX, true) . ";\n";
print "\$alphaW = " . var_export($alphaW, true) . ";\n";
