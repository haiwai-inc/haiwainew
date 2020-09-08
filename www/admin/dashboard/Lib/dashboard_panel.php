<?php
class dashboard_panel extends Model{
	protected $tableName="admin";
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");

	//后台权限
	function checkUserLevel() {
		global $conn;
		if ($_SESSION['UserLevel'] == 1) {
			return true;
		} else {
			$sql = sprintf("SELECT id FROM user_power WHERE uid=%d", $_SESSION['UserID']);
			$stmt = $conn->getRow($sql);
			if ($stmt->fetch()) {
				return true;
			} else {
				//print_r($_SESSION);
				alert("Permission Denied!");
				return false;
			}
		}
	}
}
?>