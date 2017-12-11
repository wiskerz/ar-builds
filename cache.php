<?php
require "lancers.php";
foreach($lancers as $key => $lancer) {
  	echo "Retreiving: " .  $lancer . "\n";
  	`./process.sh $lancer lancers/$key.js`;
  	sleep(1);
}
