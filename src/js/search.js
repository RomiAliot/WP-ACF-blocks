
document.addEventListener('DOMContentLoaded', async function () {


    var loadmoreResultsBtn = document.querySelector('#load-more-results-button');
    var searchTermInput = document.querySelector('#search-term');
    var searchTermButton = document.querySelector('#search-results-button');
    // Función para cargar más recursos
    async function loadMoreResults(searchTerm = '') {
        if (canLoadMoreResults) {
            try {
                let searchParams, searchParam;
                if (searchTerm == '') {
                    searchParams = new URLSearchParams(window.location.search);
                    searchParam = searchParams.get('s');
                    searchTermInput.value = searchParam;
                } else {
                    searchParam = searchTerm;
                }
                // console.log(searchParam);
                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'load_more_search_results',
                        page: page,
                        posts_per_page: 12,
                        search_param: searchParam
                    })
                });

                if (!response.ok) {
                    throw new Error('Error al cargar más eventos.');
                }

                const responseData = await response.json();
                // console.log('responseData', responseData);
                if (responseData.results_count > 0) {
                    document.getElementById('results-container').style.display = '';
                    document.getElementById('results-count').style.display = '';
                    document.getElementById('no-results-container').style.display = 'none';

                } else {
                    document.getElementById('results-container').style.display = 'none';
                    document.getElementById('results-count').style.display = 'none';
                    document.getElementById('no-results-container').style.display = '';
                }
                if (page == 1) { document.getElementById('results-container').innerHTML = ''; }
                document.getElementById('results-container').insertAdjacentHTML('beforeend', responseData.html);
                var cardElements = document.querySelectorAll('.card-search-result');

                // Texto a resaltar
                var textToHighlight = searchParam;

                // Estilo para resaltar

                // Recorrer todos los elementos
                cardElements.forEach(function (card) {
                    // Obtener el elemento h5 dentro de la tarjeta
                    var h5Element = card.querySelector('h5');

                    // Buscar el texto a resaltar dentro del elemento h5
                    var content = h5Element.textContent;
                    var regex = new RegExp(textToHighlight, 'gi'); // 'gi' para coincidencias globales sin distinguir mayúsculas y minúsculas
                    var highlightedContent = content.replace(regex, function (match) {
                        return '<span class="bg-highlight px-1">' + match + '</span>'; // Envolver el texto coincidente en un span con el estilo de resaltado
                    });

                    // Reemplazar el contenido original del h5 con el contenido resaltado
                    h5Element.innerHTML = highlightedContent;
                });
                page++;

                canLoadMoreResults = (responseData.html.trim() !== 'No hay más recursos.');

                document.getElementById('results-count').textContent = 'Showing ' + responseData.results_count + ' of ' + responseData.total_results;
                // console.log(responseData.results_count, responseData.total_results);
                if (responseData.results_count == responseData.total_results) {
                    loadmoreResultsBtn.style.display = 'none'
                } else {
                    loadmoreResultsBtn.style.display = ''
                }
                init = false;

            } catch (error) {
                console.error(error);
            }
        }
    }

    // Cargar recursos al cargar la página
    if (loadmoreResultsBtn) {
        var page = 1;
        var canLoadMoreResults = true;

        await loadMoreResults();

        // Manejar clic en el botón "Load More"
        document.getElementById('load-more-results-button').addEventListener('click', function () {

            loadMoreResults();
        });
    }
    if (searchTermButton) {



        // Manejar clic en el botón "Load More"
        searchTermButton.addEventListener('click', function () {
            page = 1;
            canLoadMoreResults = true;
            loadmoreResultsBtn.style.display = 'flex';
            loadMoreResults(searchTermInput.value);

            // Obtener la URL actual
            let url = new URL(window.location.href);

            // Obtener los parámetros de búsqueda
            let searchParams = new URLSearchParams(url.search);

            // Actualizar el valor del parámetro 's' con el valor del input
            searchParams.set('s', searchTermInput.value);

            // Reemplazar los parámetros de búsqueda en la URL actual
            history.pushState(null, '', `${url.pathname}?${searchParams.toString()}`);

            console.log('click');
        });
    }
    if (searchTermInput) {
        searchTermInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Evitar que se envíe el formulario por defecto
                page = 1;
                canLoadMoreResults = true;
                loadmoreResultsBtn.style.display = 'flex';
                loadMoreResults(searchTermInput.value);

                // Obtener la URL actual
                let url = new URL(window.location.href);

                // Obtener los parámetros de búsqueda
                let searchParams = new URLSearchParams(url.search);

                // Actualizar el valor del parámetro 's' con el valor del input
                searchParams.set('s', searchTermInput.value);

                // Reemplazar los parámetros de búsqueda en la URL actual
                history.pushState(null, '', `${url.pathname}?${searchParams.toString()}`);

                console.log('Enter pressed');
            }
        });
    }
});
