<?php

$say = $_REQUEST['say'];
$md5 = md5($say);

if (!file_exists("speech/$md5.aiff")) {
  exec("say -v Vicki -o speech/$md5.aiff --file-format=AIFF \"$say\"");
  exec("/opt/local/bin/lame speech/$md5.aiff speech/$md5.mp3");
}

header("Location: speech/$md5.mp3");
exit;
