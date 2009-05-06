<?php
/*
 * Centreon is developped with GPL Licence 2.0 :
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 * Developped by : Julien Mathis - Romain Le Merlus 
 * 
 * The Software is provided to you AS IS and WITH ALL FAULTS.
 * Centreon makes no representation and gives no warranty whatsoever,
 * whether express or implied, and without limitation, with regard to the quality,
 * any particular or intended purpose of the Software found on the Centreon web site.
 * In no event will Centreon be liable for any direct, indirect, punitive, special,
 * incidental or consequential damages however they may arise and even if Centreon has
 * been previously advised of the possibility of such damages.
 * 
 * For information : contact@centreon.com
 */

require_once "@CENTREON_ETC@/centreon.conf.php";
require_once $centreon_path . "/www/class/centreonExternalCommand.class.php";
require_once $centreon_path . "/www/class/centreonDB.class.php";
require_once $centreon_path . "/www/class/Session.class.php";
require_once $centreon_path . "/www/class/Oreon.class.php";
require_once $centreon_path . "/www/class/centreonXML.class.php";
  
Session::start();
if (!isset($_SESSION["oreon"]) || !isset($_GET["poller"]) || !isset($_GET["cmd"]) || !isset($_GET["sid"]))
	exit();

$oreon =& $_SESSION["oreon"];


$poller = $_GET["poller"];
$cmd = $_GET["cmd"];
$sid = $_GET["sid"];
$str = $_GET["str"];

$pearDB = new CentreonDB();
$DBRESULT =& $pearDB->query("SELECT session_id FROM session WHERE session.session_id = '".$sid."'");
if (!$DBRESULT->numRows())
	exit();

switch ($cmd) {
	case "SHUTDOWN_PROGRAM" : $action_name = "global_shutdown"; break;
	case "RESTART_PROGRAM" : $action_name = "global_restart"; break;
	case "DISABLE_NOTIFICATIONS" : $action_name = "global_notifications"; break;
	case "ENABLE_NOTIFICATIONS" : $action_name = "global_notifications"; break;
	case "STOP_EXECUTING_SVC_CHECKS" : $action_name = "global_service_checks"; break;
	case "START_EXECUTING_SVC_CHECKS" : $action_name = "global_service_checks"; break;
	case "STOP_ACCEPTING_PASSIVE_SVC_CHECKS" : $action_name = "global_service_passive_checks"; break;
	case "START_ACCEPTING_PASSIVE_SVC_CHECKS" : $action_name = "global_service_passive_checks"; break;
	case "STOP_EXECUTING_HOST_CHECKS" : $action_name = "global_host_checks"; break;
	case "START_EXECUTING_HOST_CHECKS" : $action_name = "global_host_checks"; break;
	case "STOP_ACCEPTING_PASSIVE_HOST_CHECKS" : $action_name = "global_host_passive_checks"; break;
	case "START_ACCEPTING_PASSIVE_HOST_CHECKS" : $action_name = "global_host_passive_checks"; break;
	case "DISABLE_EVENT_HANDLERS" : $action_name = "global_event_handler"; break;
	case "ENABLE_EVENT_HANDLERS" : $action_name = "global_event_handler"; break;
	case "STOP_OBSESSING_OVER_SVC_CHECKS" : $action_name = "global_service_obsess"; break;
	case "START_OBSESSING_OVER_SVC_CHECKS" : $action_name = "global_service_obsess"; break;
	case "STOP_OBSESSING_OVER_HOST_CHECKS" : $action_name = "global_host_obsess"; break;
	case "START_OBSESSING_OVER_HOST_CHECKS" : $action_name = "global_host_obsess"; break;
	case "DISABLE_FLAP_DETECTION" : $action_name = "global_flap_detection"; break;
	case "ENABLE_FLAP_DETECTION" : $action_name = "global_flap_detection"; break;
	case "DISABLE_PERFORMANCE_DATA" : $action_name = "global_perf_data"; break;
	case "ENABLE_PERFORMANCE_DATA" : $action_name = "global_perf_data"; break;
	default : $action_name = ""; break;
}

if (!$oreon->user->access->checkAction($action_name))
	exit();	


$command = new CentreonExternalCommand($oreon);
$command->set_process_command($cmd, $poller);
$result = $command->write();

$buffer = new CentreonXML();
$buffer->startElement("root");
$buffer->writeElement("result", $result);
$buffer->writeElement("cmd", $cmd);

if (preg_match("/^ENABLE|^START/", $cmd)) {
	$img_flag = "<img src='./img/icones/16x16/delete2.gif'>";
	$switch_cmd = str_replace("ENABLE", "DISABLE", $cmd);
	$switch_cmd = str_replace("START", "STOP", $switch_cmd);
	$switch_str = str_replace(_("Enable"), _("Disable"), $str);
	$switch_str = str_replace(_("Start"), _("Stop"), $switch_str);
}
elseif (preg_match("/^DISABLE|^STOP/", $cmd)) {
	$img_flag = "<img src='./img/icones/16x16/flag_green.gif'>";
	$switch_cmd = str_replace("DISABLE", "ENABLE", $cmd);
	$switch_cmd = str_replace("STOP", "START", $switch_cmd);
	$switch_str = str_replace(_("Disable"), _("Enable"), $str);
	$switch_str = str_replace(_("Stop"), _("Start"), $switch_str);
}
elseif (preg_match("/^SHUTDOWN/", $cmd)) {
	$img_flag = "<img src='./img/icones/16x16/stop.gif'>";
	$switch_cmd = $cmd;
	$switch_str = $str;
}
elseif (preg_match("/^RESTART/", $cmd)) {
	$img_flag = "<img src='./img/icones/16x16/refresh.gif'>";
	$switch_cmd = $cmd;
	$switch_str = $str;
}
$buffer->writeElement("img_flag", $img_flag);
$buffer->writeElement("switch_cmd", $switch_cmd);
$buffer->writeElement("switch_str", $switch_str);
$buffer->endElement();
header('Content-type: text/xml; charset=iso-8859-1');
header('Cache-Control: no-cache, must-revalidate');
$buffer->output();
?>