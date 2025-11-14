async function buscarHoteles() {
    const search = document.getElementById('search').value;
    const token = document.getElementById('token').value;
    const resultadosDiv = document.getElementById('resultados');

    if (!search.trim()) {
        alert('Ingrese un nombre o tipo de habitación para buscar.');
        return;
    }

    resultadosDiv.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Buscando hoteles...</div>';

    try {
        const formData = new FormData();
        formData.append('token', token);
        formData.append('search', search);

        const response = await fetch('api_handler.php?action=buscarHoteles', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (!data.status) {
            resultadosDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-triangle"></i> ${data.msg}</div>`;
            return;
        }

        if (data.data.length === 0) {
            resultadosDiv.innerHTML = '<div class="no-results"><i class="fas fa-info-circle"></i> No se encontraron hoteles.</div>';
            return;
        }

        let html = '';
        data.data.forEach(hotel => {
            html += `
                <div class="hotel-card">
                    <div class="hotel-header">
                        <div class="hotel-icon"><i class="fas fa-hotel"></i></div>
                        <div>
                            <h3 class="hotel-nombre">${hotel.nombre}</h3>
                            <p class="hotel-direccion">${hotel.direccion}</p>
                        </div>
                    </div>
                    <div class="hotel-info">
                        <p><i class="fas fa-phone"></i> ${hotel.telefono}</p>
                        <p><i class="fas fa-bed"></i> ${hotel.tipos_habitacion}</p>
                        <p><i class="fas fa-credit-card"></i> ${hotel.metodos_pago}</p>
                    </div>
                </div>
            `;
        });

        resultadosDiv.innerHTML = html;

    } catch (error) {
        console.error('Error:', error);
        resultadosDiv.innerHTML = '<div class="error"><i class="fas fa-exclamation-triangle"></i> Ocurrió un error al buscar los hoteles.</div>';
    }
}
