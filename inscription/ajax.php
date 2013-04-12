<?php

require_once('../include.php');

if (!isset($_GET['action']))
    die();
    
switch ($_GET['action']) {
    case 'dispos':
        if (isset($_GET['conf-id'])) {
            $id = intval($_GET['conf-id']);
            $dispos = db_query("SELECT d.id, d.periode, d.jour, r.id AS res_id
                                FROM conferences c
                                JOIN disponibilites d ON d.intervenant = c.intervenant
                                LEFT JOIN reservations r ON c.id = r.conference AND d.id = r.disponibilite
                                WHERE c.id = $id");
            $result = array();
            while($data = $dispos->fetch_assoc())
                $result[] = $data;
            echo json_encode($result);
        }
        break;
    default:
        break;
}

?>
