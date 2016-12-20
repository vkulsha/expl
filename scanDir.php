<?php
function myscandir($dir, $exp) 
{ 
	$r = array();
	if (file_exists($dir)) {
		$files = scandir($dir);
		foreach ($files as $key=>$fname ) { 
			//echo iconv("cp1251", "utf-8", $fname);
			//echo mb_convert_encoding($fname, "UTF-8", "Windows-1251");
			mb_detect_encoding($fname);
			$fname = mb_convert_encoding($fname, "UTF-8", "Windows-1251");
			$fname = htmlentities($fname, ENT_QUOTES);
			if (preg_match($exp, $fname)) { 
				if ($fname != "." && $fname != "..") {
					$ext = explode(".", $fname);
					$ext = end($ext);
					if ($ext) {
						if (!array_key_exists($ext,$r)) {
							$r[$ext] = [];
						}
						$r[$ext][] = $fname;
					}
				}
			} 
		}
	}
	
/*    $dh = @opendir($dir); 
    if ($dh) { 
        while (($fname = readdir($dh)) !== false) { 
            if (preg_match($exp, $fname)) { 
				if ($fname != "." && $fname != "..") {
					$ext = explode(".", $fname)[1];
					if ($ext) {
						if (!array_key_exists($ext,$r)) {
							$r[$ext] = [];
						}
						$r[$ext][] = $fname;
						//echo $fname;
					}
				}
            } 
        } 
        closedir($dh); 
        asort($r); 
    }*/
    return($r); 
} 

header('Content-Type: text/html; charset=utf-8');
$ext = isset($_GET['ext']) ? $_GET['ext'] : "";
$r = myscandir($_GET['path'], '/.*\.'.$ext.'/i', 'name');
echo json_encode ($r, JSON_UNESCAPED_UNICODE); //'name','ctime'

?>