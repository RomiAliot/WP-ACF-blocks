
document.addEventListener('DOMContentLoaded', async function () {


    var loadmoreEventsBtn = document.querySelector('#load-more-events-button');
    var loadmoreEventsPastBtn = document.querySelector('#load-more-events-past-button');
    var loader = document.querySelector('#loader');
    var loaderPast = document.querySelector('#loader-past');
    document.addEventListener('click', function (event) {
        const parentGroup = event.target.closest('.filtergroup');
        if (parentGroup) {
            parentGroup.classList.toggle('open');
        }
    });
    // Función para cargar más recursos
    async function loadMoreEvents() {
        if (canLoadMoreEvents) {
            try {
                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'load_more_events',
                        page: page,
                        posts_per_page: 14,
                        init: init,
                        regions: regions
                    })
                });

                if (!response.ok) {
                    throw new Error('Error al cargar más recursos.');
                }

                const responseData = await response.json();
                console.log(responseData);
                loader.classList.add('hidden');
                if (init === true || isFilter === true) {
                    document.getElementById('events-container').innerHTML = '';
                }
                document.getElementById('events-container').insertAdjacentHTML('beforeend', responseData.html);

                if (init === true) { page = 1; } else { page++; }
                console.log(page, init);
                canLoadMoreEvents = (responseData.html.trim() !== 'No hay más recursos.');

                document.getElementById('events-count').textContent = 'Showing ' + responseData.events_count + ' of ' + responseData.total_events;
                //console.log(responseData.events_count, responseData.total_events);
                if (responseData.events_count == responseData.total_events) {
                    loadmoreEventsBtn.style.display = 'none'
                } else {
                    loadmoreEventsBtn.style.display = ''
                }
                console.log('total', responseData.events_count);
                if (responseData.events_count == 0) {
                    document.getElementById('events-count').textContent = "We're sorry, but there are no events in the region you selected.";
                }
                init = false;

            } catch (error) {
                console.error(error);
            }
        }
    }

    // Cargar recursos al cargar la página
    if (loadmoreEventsBtn) {
        var page = 1;
        var canLoadMoreEvents = true;
        var init = true;
        var regions = [];
        var isFilter = false;
        await loadMoreEvents();

        // Manejar clic en el botón "Load More"
        document.getElementById('load-more-events-button').addEventListener('click', function () {
            isFilter = false;

            loadMoreEvents();
        });
        const buttons = document.querySelectorAll('.filter');


        buttons.forEach(button => {
            button.addEventListener('click', function () {
                const parent = this.parentElement;
                const id = this.id;
                isFilter = true;
                parent.classList.toggle('active');

                if (parent.classList.contains('active')) {
                    if (!regions.includes(id)) {
                        regions.push(id);
                    }
                } else {
                    const index = regions.indexOf(id);
                    if (index > -1) {
                        regions.splice(index, 1);
                    }
                }
                init = true;
                page = 1;
                loadMoreEvents();
                // console.log(regions);

            });
        });
    }
    async function loadMoreEventsPast() {
        if (canLoadMoreEventsPast) {
            try {
                //  console.log('init', initPast);
                loaderPast.classList.remove('hidden');
                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'load_more_events_past',
                        page: pagePast,
                        posts_per_page: 12,
                        year: year
                    })
                });

                if (!response.ok) {
                    throw new Error('Error al cargar más recursos.');
                }

                const responseData = await response.json();
                loaderPast.classList.add('hidden');
                //  console.log(responseData.html);

                // Obtener las claves (años) del objeto y ordenar de forma descendente
                let years = Object.keys(responseData.html).sort((a, b) => b - a);

                // Obtener el contenedor de posts pasados
                let eventsContainer = document.getElementById('events-past-container');

                // Recorrer cada año y sus posts
                years.forEach(year => {
                    // Verificar si el contenedor del año ya existe
                    let yearContainer = document.getElementById('events-' + year);
                    if (!yearContainer) {
                        // Si no existe, crear un contenedor div para el año con un ID específico
                        yearContainer = document.createElement('div');
                        yearContainer.id = 'events-' + year;
                        yearContainer.classList.add('flex', 'flex-col', 'md:grid', 'md:grid-cols-2', 'lg:grid-cols-3', 'gap-6');

                        // Agregar el bloque HTML con el año al contenedor
                        yearContainer.innerHTML = `
            <div class="col-span-1 md:col-span-2 lg:col-span-3 flex gap-4 justify-start items-center">
                <span class="text-kicker text-bold-600">${year}</span>
                <hr class="flex-1 text-bold-400 w-full" />
            </div>
        `;

                        // Agregar el contenedor del año al contenedor principal de posts pasados
                        eventsContainer.appendChild(yearContainer);
                    }

                    // Recorrer cada post del año actual y agregarlo al contenedor del año
                    responseData.html[year].forEach(post => {
                        // Agregar el p al contenedor del año
                        yearContainer.insertAdjacentHTML('beforeend', post)
                    });
                });

                //document.getElementById('events-past-container').insertAdjacentHTML('beforeend', responseData.html);
                pagePast++;

                //canLoadMoreEventsPast = (responseData.html.trim() !== 'No hay más recursos.');

                document.getElementById('events-past-count').textContent = 'Showing ' + responseData.events_count + ' of ' + responseData.total_events;
                //   console.log(responseData.events_count, responseData.total_events);

                if (responseData.events_count == responseData.total_events) {
                    loadmoreEventsPastBtn.style.display = 'none'
                } else {
                    loadmoreEventsPastBtn.style.display = ''
                }
                console.log('total', responseData.events_count);
                if (responseData.events_count == 0) {
                    document.getElementById('events-past-count').textContent = "We're sorry, but there are no events in the region you selected.";
                }
                initPast = false;

            } catch (error) {
                console.error(error);
            }
        }
    }

    // Cargar recursos al cargar la página
    if (loadmoreEventsPastBtn) {
        var year = '';
        var pagePast = 1;
        var canLoadMoreEventsPast = true;
        var initPast = true;
        await loadMoreEventsPast();

        // Manejar clic en el botón "Load More"
        document.getElementById('load-more-events-past-button').addEventListener('click', function () {

            loadMoreEventsPast();
        });
    }

});
