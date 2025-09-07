<?php
session_start();
echo "<h2>Prueba de Sesión</h2>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['user_id'])) {
    echo "<p style='color:green;'>✅ Sesión activa</p>";
} else {
    echo "<p style='color:red;'>❌ Sesión no iniciada</p>";
}
?>