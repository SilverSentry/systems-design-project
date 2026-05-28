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

    searchInput.addEventListener("input", function() {
        clearTimeout(timeout);
        const query = searchInput.value.trim().toLowerCase();

        if (query.length < 3) {
            resultsList.classList.add("d-none");
            return;
        }

        timeout = setTimeout(() => {
            // Usar URL relativa con parámetro `p` para que funcione en subcarpetas
            // Ejemplo resultante: /ordo_stetic/?p=api/search&q=diabetes
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

    // Botón "Anexar a la Lista" (Se mantiene igual)
    addBtn.addEventListener("click", function() {
        if (!selectedId || !selectedTerm) {
            alert("Por favor, selecciona un término válido del buscador.");
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