const mediaItems = [
    { type: 'image', url: 'https://placehold.co/1000x800/87CEEB/fff?text=Imagen+1' },
    { type: 'image', url: 'https://placehold.co/1000x800/61A4C2/fff?text=Imagen+2' },
    { type: 'image', url: 'https://placehold.co/1000x800/3483fa/fff?text=Imagen+3' },
    { type: 'video', url: 'https://www.youtube.com/embed/dQw4w9WgXcQ' } // Ejemplo de un video de YouTube
];

let currentIndex = 0;

function selectImage(element, index) {
    // Elimina la clase 'active' de todas las miniaturas
    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach(thumb => {
        thumb.classList.remove('active');
    });
    
    // Añade la clase 'active' a la miniatura seleccionada
    element.classList.add('active');
    
    // Cambia la imagen principal por la seleccionada
    const mainImageContainer = document.getElementById('mainImage');
    const newImg = document.createElement('img');
    newImg.src = mediaItems[index].url;
    newImg.alt = `Imagen ${index + 1}`;
    mainImageContainer.innerHTML = '';
    mainImageContainer.appendChild(newImg);
    
    // Actualiza el índice
    currentIndex = index;
}

function openGallery(index) {
    const galleryModal = document.getElementById('galleryModal');
    galleryModal.classList.add('show');
    displayMedia(index);
}

function closeGallery() {
    const galleryModal = document.getElementById('galleryModal');
    galleryModal.classList.remove('show');
    // Detener el video si se está reproduciendo al cerrar
    const currentContent = document.getElementById('galleryContent');
    currentContent.innerHTML = '';
}

function displayMedia(index) {
    if (index < 0 || index >= mediaItems.length) return;
    
    currentIndex = index;
    const item = mediaItems[currentIndex];
    const galleryContent = document.getElementById('galleryContent');
    
    galleryContent.innerHTML = ''; // Limpiar el contenido anterior

    if (item.type === 'image') {
        const img = document.createElement('img');
        img.src = item.url;
        img.alt = `Imagen ${currentIndex + 1}`;
        galleryContent.appendChild(img);
    } else if (item.type === 'video') {
        const iframe = document.createElement('iframe');
        iframe.src = item.url + '?autoplay=1';
        iframe.setAttribute('width', '800');
        iframe.setAttribute('height', '450');
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
        iframe.setAttribute('allowfullscreen', '');
        galleryContent.appendChild(iframe);
    }

    // Ocultar/mostrar botones de navegación
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    prevBtn.classList.toggle('hidden', currentIndex === 0);
    nextBtn.classList.toggle('hidden', currentIndex === mediaItems.length - 1);
}

// Event Listeners para la navegación de la galería
if(document.getElementById('prevBtn')){
    document.getElementById('prevBtn').addEventListener('click', () => {
        if (currentIndex > 0) {
            displayMedia(currentIndex - 1);
        }
    });
}
if(document.getElementById('nextBtn')){
    document.getElementById('nextBtn').addEventListener('click', () => {
        if (currentIndex < mediaItems.length - 1) {
            displayMedia(currentIndex + 1);
        }
    });
}
if(document.getElementById('closeGalleryBtn')){
    document.getElementById('closeGalleryBtn').addEventListener('click', closeGallery);
}


// Funciones para el menú desplegable y Gemini (sin cambios)
function closeDropdown() {
    const menu = document.getElementById('categoryDropdownMenu');
    menu.classList.remove('show');
}
if(document.getElementById('categoryDropdownBtn')){
    document.getElementById('categoryDropdownBtn').addEventListener('click', function(event) {
        const menu = document.getElementById('categoryDropdownMenu');
        menu.classList.toggle('show');
        event.stopPropagation();
    });
}

document.addEventListener('click', function(event) {
    const dropdownBtn = document.getElementById('categoryDropdownBtn');
    const dropdownMenu = document.getElementById('categoryDropdownMenu');
    if (dropdownBtn && !dropdownBtn.contains(event.target) && dropdownMenu && !dropdownMenu.contains(event.target)) {
        closeDropdown();
    }
});

const API_URL = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-05-20:generateContent?key=";
const API_KEY = "";

async function askGemini() {
    const questionInput = document.getElementById('questionInput');
    const question = questionInput.value;
    const answerArea = document.getElementById('answerArea');
    const geminiAnswer = document.getElementById('geminiAnswer');
    const productTitle = document.getElementById('productTitle').textContent;

    if (question.trim() === "") {
        return;
    }

    answerArea.classList.remove('hidden');
    geminiAnswer.innerHTML = 'Cargando...';

    const userQuery = `Soy un cliente en una tienda en línea y tengo una pregunta sobre el producto: "${productTitle}". Mi pregunta es: "${question}". Por favor, responde de forma concisa como si fueras un asistente de ventas.`;
    
    const payload = {
        contents: [{ parts: [{ text: userQuery }] }]
    };

    try {
        const response = await fetch(API_URL + API_KEY, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await response.json();
        const text = result?.candidates?.[0]?.content?.parts?.[0]?.text || 'No pude generar una respuesta. Por favor, inténtalo de nuevo.';
        geminiAnswer.textContent = text;
    } catch (error) {
        console.error("Error al llamar a la API de Gemini:", error);
        geminiAnswer.textContent = "Lo siento, hubo un error al obtener la respuesta.";
    }
}

const logoDot = document.getElementById("logoDot");

function blinkLogo() {
    let blinks = 0;
    const interval = setInterval(() => {
        if (!logoDot) return;
        logoDot.setAttribute("fill", logoDot.getAttribute("fill") === "#333" ? "#fff" : "#333");
        blinks++;
        if (blinks >= 10) { // 5 parpadeos (encendido+apagado)
            clearInterval(interval);
            logoDot.setAttribute("fill", "#333"); // volver al color original
            setTimeout(blinkLogo, 10000); // esperar 10 seg y reiniciar ciclo
        }
    }, 500); // cada 0.5s cambia color
}

// Inicia el ciclo después de 5s
setTimeout(blinkLogo, 5000);


// Toggle favoritos
document.querySelectorAll('.btn-toggle-favorito').forEach(button => {
    button.addEventListener('click', function() {
        const articuloId = this.dataset.id;
        const icon = this.querySelector('.icon-favorito');
        
        fetch('http://localhost/megatec/public/toggle_favorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'articulo_id=' + articuloId
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'added') {
                icon.textContent = '❤️';
                icon.style.color = '#E57373';
            } else {
                icon.textContent = '🤍';
                icon.style.color = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar favoritos');
        });
    });
});

// Agregar al carrito
document.querySelectorAll('.btn-agregar-carrito').forEach(button => {
    button.addEventListener('click', function() {
        const articuloId = this.dataset.id;
        
        fetch('http://localhost/megatec/public/agregar_al_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'articulo_id=' + articuloId + '&cantidad=1&redirect=0'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al agregar al carrito');
        });
    });
});

// Dropdown compartir
document.querySelectorAll('.dropdown').forEach(dropdown => {
    const button = dropdown.querySelector('button');
    const content = dropdown.querySelector('.dropdown-content');
    
    button.addEventListener('click', function(e) {
        e.stopPropagation();
        document.querySelectorAll('.dropdown-content').forEach(dc => {
            if (dc !== content) dc.style.display = 'none';
        });
        content.style.display = content.style.display === 'block' ? 'none' : 'block';
    });
});

// Cerrar dropdowns al hacer clic fuera
document.addEventListener('click', function() {
    document.querySelectorAll('.dropdown-content').forEach(dc => {
        dc.style.display = 'none';
    });
});