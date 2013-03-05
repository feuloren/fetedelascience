<?php

if (!isset($_GET['action']))
    die();
    
switch ($_GET['action']) {
    case 'dispos':
        if (isset($_GET['conf-id'])) {
            $id = intval($_GET['conf-id']);
            $dispos = db_query("SELECT d.id, d.periode, d.jour FROM conferences13 c
                                JOIN disponibilites13 d ON d.intervenant = c.intervenant
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
