<?php
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2 class="text-2xl font-bold mb-4">Editar Usuario</h2>

<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:600px; margin:2rem auto;">
    <form method="POST" action="<?= URL_BASE ?>dashboard/administrador/usuarios.php?action=update&id=<?= $usuario['id'] ?>" enctype="multipart/form-data">
        <div style="margin-bottom:1rem;">
            <label>Nombre:</label><br>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" style="padding:0.5rem; width:100%;">
        </div>
        <div style="margin-bottom:1rem;">
            <label>Contraseña (dejar en blanco para no cambiar):</label><br>
            <input type="password" name="password" style="padding:0.5rem; width:100%;">
        </div>
        <div style="margin-bottom:1rem;">
            <label>Avatar:</label><br>
            <input type="file" name="avatar" accept="image/*" style="padding:0.5rem; width:100%;">
        </div>
        <button type="submit" style="background:#4DB6AC; color:white; padding:0.5rem 1rem; border:none; border-radius:4px; cursor:pointer;">
            Actualizar Usuario
        </button>
    </form>
</div>

<?php
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php';
?>