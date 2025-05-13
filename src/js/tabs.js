document.addEventListener("DOMContentLoaded", function () {
    // Obtener los elementos de los tabs y sus contenidos
    const tabs = document.querySelectorAll('[data-tab]');
    const tabContents = document.querySelectorAll('.tabContent');

    // Agregar evento click a cada tab
    tabs.forEach(tab => {

        tab.addEventListener('click', () => {
            // Obtener el ID del tab seleccionado
            const tabId = tab.dataset.tab;

            // Ocultar todos los contenidos de los tabs
            tabContents.forEach(content => {
                content.style.display = 'none';
            });

            // Mostrar el contenido del tab seleccionado
            document.getElementById(tabId).style.display = 'block';

            // Remover la clase 'active' de todos los tabs
            tabs.forEach(t => {
                t.classList.remove('active');
            });

            // Agregar la clase 'active' al tab seleccionado
            tab.classList.add('active');
        });

    });
});
