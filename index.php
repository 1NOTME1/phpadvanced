<?php
require_once("./InternalEventPage.php");
require_once("./TaskPage.php");

$action = $_GET['action'] ?? 'events';

switch ($action) {
    case 'tasks':
        (new TaskPage())->initialize();
        break;
    case 'events':
    default:
        (new InternalEventPage())->initialize();
        break;
}
?>
