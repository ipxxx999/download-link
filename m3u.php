<?php
// Incluir el archivo de configuración // Include the configuration file
require_once 'config.php';
    

// Coge la contraseña de la cadena de consulta // Grab the password from the query string
$oauthPass = trim($_SERVER['QUERY_STRING']);

// Verify the oauth password // Verifique la contraseña de oauth
if($oauthPass != OAUTH_PASSWORD){
    echo "<div class='btn btn-warning text-center'>Sin nada";
    // Devuelve el error 404, si no es una ruta correcta // Return 404 error, if not a correct path
    header("HTTP/1.0 404 Not Found");
    exit;
}else{    
    // Cree una lista de enlaces para mostrar los archivos de descarga // Create a list of links to display the download files
    $download_links = array();
    
    // Si los archivos existen // If the files exist
    if(is_array($files)){
        foreach($files as $fid => $file){

            // Codificar el ID del archivo // Encode the file ID
            $fid = base64_encode($fid);

            // Genere una nueva clave única // Generate new unique 
            $key = uniqid(time().'-key',TRUE);
            
            // Generar enlace de descarga // Generate download link
            $download_link = DOWNLOAD_PATH."?fid=$fid&key=".$key; 
            
            // Agregar enlace de descarga a la lista // Add download link to the list
            $download_links[] = array(
                'link' => $download_link
            );
            
            // Create a protected directory to store keys // Crea un directorio protegido para almacenar claves
            if(!is_dir(TOKEN_DIR)) {
                mkdir(TOKEN_DIR);
                $file = fopen(TOKEN_DIR.'/.htaccess','w');
                fwrite($file,"Order allow,deny\nDeny from all");
                fclose($file);
            }
            
            // Escriba la clave en la lista de claves // Write the key to the keys list
            $file = fopen(TOKEN_DIR.'/keys','a');
            fwrite($file, "{$key}\n");
            fclose($file);
        }
    }
}    
?>

<!-- Lista todos los enlaces de descarga --> <!-- List all the download links -->
<?php if(!empty($download_links)){ ?>
    <ul>
    <?php foreach($download_links as $download){ ?>            
        <li><a href="<?php echo $download['link']; ?>"><?php echo  $download['link']; ?></a></li>
    <?php } ?>
    </ul>
<?php }else{ ?>
    <p><center> <div class='btn btn-warning text-center'>No se encuentran los enlaces...</p>
<?php } ?>
    <!-- color para el mensaje --> 
    <link rel="stylesheet" href="./css/bootstrap.css"> 