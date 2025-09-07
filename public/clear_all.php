<?php
session_start();
session_destroy();
echo "Cache y sesiones limpiadas. <a href='index.php'>Volver al inicio</a>";
?>