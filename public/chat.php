<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Chat.php';

requireLogin();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>Chat - Mensajes Recibidos</h2>
<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto;">
    <div style="height:400px; overflow-y:auto; border:1px solid #ddd; padding:1rem; margin-bottom:1rem;">
        <div style="margin-bottom:1rem; padding:1rem; background:#f1f8e9; border-radius:8px; position:relative;">
            <button style="position:absolute; top:5px; right:5px; background:#E57373; color:white; border:none; border-radius:50%; width:30px; height:30px; cursor:pointer;" onclick="confirmarEliminacion()">
                🗑️
            </button>
            <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.5rem;">
                <div style="width:40px; height:40px; background:#ddd; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    <span>👤</span>
                </div>
                <div>
                    <strong>Cliente 1</strong>
                    <p style="margin:0; font-size:0.8rem;">Hace 5 minutos</p>
                </div>
            </div>
            <p>¿Tienen stock de este producto?</p>
        </div>
        
        <div style="margin-bottom:1rem; padding:1rem; background:#e3f2fd; border-radius:8px; position:relative;">
            <button style="position:absolute; top:5px; right:5px; background:#E57373; color:white; border:none; border-radius:50%; width:30px; height:30px; cursor:pointer;" onclick="confirmarEliminacion()">
                🗑️
            </button>
            <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.5rem;">
                <div style="width:40px; height:40px; background:#ddd; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    <span>👤</span>
                </div>
                <div>
                    <strong>Cliente 2</strong>
                    <p style="margin:0; font-size:0.8rem;">Hace 2 horas</p>
                </div>
            </div>
            <p>¿Pueden enviar a mi domicilio?</p>
        </div>
    </div>
    
    <form method="POST" style="display:flex; gap:0.5rem;">
        <input type="text" placeholder="Escribe tu mensaje..." style="flex:1; padding:0.5rem; border:1px solid #ddd; border-radius:4px;">
        <input type="file" style="padding:0.5rem; border:1px solid #ddd; border-radius:4px;">
        <button type="submit" style="background:#4DB6AC; color:white; padding:0.5rem 1rem; border:none; border-radius:4px; cursor:pointer;">
            Enviar
        </button>
    </form>
</div>

<script>
function confirmarEliminacion() {
    if (confirm("ALERTA, ¿estás seguro de ELIMINAR este CHAT? ⚠️")) {
        alert("Chat eliminado correctamente.");
    }
}
</script>

<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>