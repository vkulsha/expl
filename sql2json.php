<?php
require('conn.php');
header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');

set_error_handler("warning_handler", E_WARNING);

function warning_handler($errno, $errstr) {
	throw new ErrorException();
}

$jsonResult = array(
	"columns" => ["result"],
	"data" => [[false]]
);

$query = "";
if (isset($_GET['q'])) {
	$query = $_GET['q'];
} else if (isset($_POST['q'])) {
	$query = $_POST['q'];
};

if ($query)
{
	if (strpos(strtolower($query), 'create') !== false ||
		strpos(strtolower($query), 'alter')  !== false || 
		strpos(strtolower($query), 'drop')   !== false ||
		strpos(strtolower($query), 'insert') !== false ||
		strpos(strtolower($query), 'update') !== false ||
		strpos(strtolower($query), 'delete') !== false)
	{
		try
		{
			$result = $explDb->exec($query);
			$jsonResult = array(
				"columns" => ["result"],
				"data" => [[$result]]
			);
		} catch (Exception $e) {
			$jsonResult = array(
				"columns" => ["result"],
				"data" => [[$e]]
			);
			
		}
	} elseif (strpos(strtolower($query), 'select') !== false || strpos(strtolower($query), 'pragma') !== false || strpos(strtolower($query), 'show') !== false)
	{
		try
		{
			if ($explDbType != 'sqlite') {
				$query = strtolower($query);
			}
			$result = $explDb->query($query, PDO::FETCH_NUM);
			if ($result) {
						
				$columns = array();
				if (strpos(strtolower($query), 'pragma') === true) {
					$columns = explode(",", trim(explode("from", explode("select", strtolower($query))[1])[0]));
					$tableName = trim(explode(" ", trim(explode("from", strtolower($query))[1]))[0]);

					try
					{
						$resultColumns = ($explDbType=='sqlite') ? 
							$explDb->query("pragma table_info($tableName)", PDO::FETCH_NUM) : 
							$explDb->query("show columns from $tableName", PDO::FETCH_NUM);
					} catch (Exception $e){}
					
					
					if (count($columns) == 1 && $columns[0] == "*") {
						$columns = [];
						foreach ($resultColumns as $rowNum=>$row)
						{
							$columns[] = ($explDbType=='sqlite') ? $row[1] : $row[0];
						}
					} else {
						foreach ($columns as $ind=>$val)
						{
							$columns[$ind] = trim($val);
						}
					}
					$colsCount = count($columns);
					
				} else {
					$colsCount = $result->columnCount();
					
					$colNum = 0;
					while ($colsCount > $colNum) 
					{
						$fieldName = $result->getColumnMeta($colNum)['name'];
						$columns[] = $fieldName;
						$colNum++;
					}
				}

				$data = $result->fetchAll(PDO::FETCH_NUM);
				
				$jsonResult = array(
					"columns" => $columns,
					"data" => $data
				);
			};

		} catch (Exception $e) {
			$jsonResult = array(
				"columns" => ["result"],
				"data" => [[$e]]
			);
			
		}
	}
}

echo json_encode($jsonResult, JSON_UNESCAPED_UNICODE);

?>