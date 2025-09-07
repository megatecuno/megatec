<?php
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>Gestionar Permisos de Operarios</h2>

<?php foreach ($operarios as $operario): ?>
<form method="POST" action="<?= URL_BASE ?>dashboard/administrador/actualizar_permisos.php">
    <input type="hidden" name="operario_id" value="<?= $operario['id'] ?>">
    <div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:600px; margin:2rem auto;">
        <h3><?= htmlspecialchars($operario['nombre']) ?></h3>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
            <div>
                <label>
                    <input type="checkbox" name="subir_banners" <?= $operario['permisos']['subir_banners'] ? 'checked' : '' ?>>
                    Subir Banners
                </label>
            </div>
            <div>
                <label>
                    <input type="checkbox" name="modificar_perfiles_usuarios" <?= $operario['permisos']['modificar_perfiles_usuarios'] ? 'checked' : '' ?>>
                    Modificar Perfiles de Usuarios
                </label>
            </div>
            <div>
                <label>
                    <input type="checkbox" name="usar_chat" <?= $operario['permisos']['usar_chat'] ? 'checked' : '' ?>>
                    Usar Chat
                </label>
            </div>
            <div>
                <label>
                    <input type="checkbox" name="publicar_contenido" <?= $operario['permisos']['publicar_contenido'] ? 'checked' : '' ?>>
                    Publicar Contenido
                </label>
            </div>
            <div>
                <label>
                    <input type="checkbox" name="cambiar_perfil" <?= $operario['permisos']['cambiar_perfil'] ? 'checked' : '' ?>>
                    Cambiar su Perfil
                </label>
            </div>
        </div>
        <button type="submit" style="background:#4DB6AC; color:white; padding:0.5rem 1rem; border:none; border-radius:4px; cursor:pointer; margin-top:1rem;">
            Actualizar Permisos
        </button>
    </div>
</form>
<?php endforeach; ?>

<?php
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php';
?>