<?
function quo($chaine){
  /* rajoute les quotes qui vont bien */
	if (!get_magic_quotes_gpc()) {
                return(addslashes($chaine));
        } else {
                return($chaine);
        }
}

function unquo($chaine) {
 /* enleve les quotes qui vont bien */
        return(stripslahses($chaine));
}

?>            
