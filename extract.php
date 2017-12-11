#!/bin/php
<?php
/**
 * This is to simply parse the Wiki infobox sections, very rudimentary so might need some update
 */
$d = array();
$a = array();

while($f = fgets(STDIN)){
   if(preg_match('/\ \|\ (.*)=(.*)/', $f, $matches))
   {
	if(count($matches) == 3)
	{
		$key = trim($matches[1]);
		$val = trim($matches[2]);
		$val = preg_replace_callback(
            "/{{([^}\|]*?)\|(.*?)}}/",
            function($matches){
				if(count($matches) < 3)
					return $matches[1];
				else if(trim($matches[2]) == "")
          return $matches[1];
        else
					return $matches[2];
			}, $val);
		$val = preg_replace_callback(
            "/{{([^\|]*?)}}/",
            function($matches){
				return $matches[1];
			}, $val);

    $val = str_replace("'''", "", $val);
    $val = str_replace("#var:", "", $val);
    $val = str_replace("&lt;br>", " ", $val);

		//echo "$key : $val\n";
		if(preg_match('/Ability ([0-9]+) (.*)/', $key, $mab)) {
			$a_num = ((int) $mab[1]) - 1;
			$a_dat = trim($mab[2]);
			if(preg_match('/Mod ([0-9]+) (.*)/', $a_dat, $mod)) {
				$m_num = ((int) $mod[1]) - 1;
				$m_dat = trim($mod[2]);
				$a[$a_num]['mods'][$m_num][$m_dat] = $val;
			}
			else
				$a[$a_num][$a_dat] = $val;
		}
		else {
			$d[$key] = $val;
		}
	}
   }
}
echo json_encode(array('info' => $d, 'abilities' => $a)) . "\n";
