document.addEventListener("DOMContentLoaded", function() {

    const searchInput = document.getElementById("searchSnomed");
    const resultsList = document.getElementById("snomedResults");
    const tipoSelect = document.getElementById("tipoAntecedente");
    const addBtn = document.getElementById("addAntecedenteBtn");
    const acumuladosDiv = document.getElementById("listaAntecedentesAcumulados");
    const hiddenContainer = document.getElementById("hiddenInputsContainer");
    const placeholder = document.getElementById("vacioPlaceholder");

    let selectedTerm = null;
    let selectedId = null;
    let timeout = null;
    let itemCounter = 0;

    const createClientForm = document.getElementById("createClientForm");

    //Constante para seleccionar todos los input
    const inputs = createClientForm.querySelectorAll('input');

        inputs.forEach(input => {
            input.addEventListener("input", function() {

                //Apenas se empiece a escribir, se quita la clase de error de Bootstrap
                if (this.classList.contains("is-invalid")) {

                    this.classList.remove("is-invalid");

                }
                
            });
        });

   //Bloque para manejar los mensajes de error 
   if (createClientForm) {

    //Se usam async/await
    createClientForm.addEventListener("submit", async (e) => {
        e.preventDefault(); 

        const submitBtn = document.getElementById("submitBtn");
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Validando...`;

        const formData = new FormData(e.target);
        const urlAction = e.target.getAttribute('action');

        //Configuramos la alerta base con un Mixin reutilizable
        const Toast = Swal.mixin({
            toast: true,
            position: "bottom",      
            showConfirmButton: false,
            timer: 2000,
            customClass: {
                popup: 'custom-swal-rect'
            }
        });

        //1. Limpiar todos los bordes rojos previos al inicio de la validación
        const allInputs = e.target.querySelectorAll('.form-control');
        allInputs.forEach(input => input.classList.remove("is-invalid"));

        try {
            const response = await fetch(urlAction, {
                method: "POST",
                body: formData
            });

            //Si el servidor responde un error fatal (ej: 500 o 404), saltamos al catch
            if (!response.ok) throw new Error("Error en la respuesta del servidor");

            const data = await response.json();

            if (data.status === 'success') {
                submitBtn.innerText = "¡Acceso concedido!";
                window.location.href = data.redirect;
            } else {

                //Restablecer botón en caso de error de validación
                submitBtn.disabled = false;
                submitBtn.innerHTML = "<i class='bi bi-box-arrow-in-right'></i> Registrar cliente";

                //Mostrar mensaje de error enviado desde PHP
                Toast.fire({
                    icon: "error",
                    title: data.message
                });

                //2. Pintar bordes rojos según la respuesta
                if (data.field === 'dni') {
                    const dniInput = document.getElementById("dni"); //Asegúrate de que el input tenga id="dni"

                    if (dniInput) dniInput.classList.add("is-invalid");

                } else if (data.field === 'all') {
                    allInputs.forEach(input => input.classList.add("is-invalid"));
                }
            }

        } catch (error) {

            //Manejo de caídas de conexión o errores sintácticos de PHP (HTML en lugar de JSON)
            submitBtn.disabled = false;
            submitBtn.innerHTML = "<i class='bi bi-box-arrow-in-right'></i> Registrar cliente";
            console.error("Error capturado: ", error);

            Toast.fire({
                icon: "error",
                title: "Error de procesamiento en el servidor"
            });
        }
    });
}

    //Evento para el buscador de antecedentes
    searchInput.addEventListener("input", function() {
        clearTimeout(timeout);
        
        //Si el usuario escribe o borra, reseteamos la selección previa
        //Esto para obligarlo a hacer clic en una opción de la lista desplegable nueva
        selectedId = null;
        selectedTerm = null;

        const query = searchInput.value.trim().toLowerCase();

        if (query.length < 3) {
            resultsList.classList.add("d-none");
            return;
        }

        timeout = setTimeout(() => {

            //URL relativa con parámetro `p` para que funcione en subcarpetas
            //Ejemplo resultante: /StudioOrdoStetic/?p=api/search&q=diabetes
            const url = `?p=api/search&q=${encodeURIComponent(query)}`;

            fetch(url)

            .then(res => {
                if (!res.ok) throw new Error('Respuesta de la API no OK');
                return res.json();
            })

            .then(data => {

                resultsList.innerHTML = '';

                if (data.items && data.items.length > 0) {
                    resultsList.classList.remove('d-none');

                    data.items.forEach(item => {
                        const term = item.term || item.prefLabel || '';
                        const conceptId = (item.concept && item.concept.conceptId) ? item.concept.conceptId : (item.ui || 'S/N');

                        const li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action';
                        li.style.cursor = 'pointer';
                        li.innerHTML = `<strong>${term}</strong> <small class="text-muted">(${conceptId})</small>`;

                        li.addEventListener('click', function() {
                            searchInput.value = term;
                            selectedId = conceptId;
                            selectedTerm = term;
                            resultsList.classList.add('d-none');
                        });

                        resultsList.appendChild(li);

                    });

                } else {
                    resultsList.classList.add('d-none');
                }
            })

            .catch(err => {
                console.error(`Error fetching ${url}:`, err);
                resultsList.classList.add('d-none');
            });

        }, 300);

    });

    //Botón "Anexar"
    addBtn.addEventListener("click", function() {
        if (!selectedId || !selectedTerm) {
            if (searchInput) searchInput.classList.add("is-invalid");
            showToastError("Por favor, selecciona un término válido del buscador.");
            return;
        }

        if (placeholder) placeholder.remove();

        const tipoId = tipoSelect.value;
        const tipoTexto = tipoSelect.options[tipoSelect.selectedIndex].text;

        const badge = document.createElement("div");
        badge.className = "badge bg-dark text-wrap p-2 m-1 align-middle d-inline-flex align-items-center";
        badge.id = `badge-${itemCounter}`;
        badge.innerHTML = `
            <span class="me-2">[${tipoTexto}] ${selectedTerm}</span>
            <button type="button" class="btn-close btn-close-white btn-sm" onclick="removeAntecedente(${itemCounter})"></button>
        `;
        acumuladosDiv.appendChild(badge);

        hiddenContainer.innerHTML += `
            <div id="inputs-${itemCounter}">
                <input type="hidden" name="antecedentes[${itemCounter}][tipo_id]" value="${tipoId}">
                <input type="hidden" name="antecedentes[${itemCounter}][concept_id]" value="${selectedId}">
                <input type="hidden" name="antecedentes[${itemCounter}][term_name]" value="${selectedTerm}">
            </div>
        `;

        searchInput.value = "";
        selectedId = null;
        selectedTerm = null;
        itemCounter++;
    });
});

function removeAntecedente(id) {
    document.getElementById(`badge-${id}`).remove();
    document.getElementById(`inputs-${id}`).remove();
}