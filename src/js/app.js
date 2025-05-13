const { ready } = require("./functions");
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import './events.js';
import './search.js';
import './slider.js';
import './tabs.js';
import './accordion.js';
import { useMegaMenu, useMobileMegaMenu } from "./megamenu";
import { useSalesForm } from "./sales-form";
import { initCopiable } from "./copiable";
import { useLoadMore } from "./load-more-posts";
import { syncSectionsWithNav } from './articles-archive';
import { useServiceTags, useMobileServiceTags, useMasonry } from "./single-service";
import { useResourceLoginForm } from "./single-resource.js";
import { useMailVerfication } from "./email-verification.js";

gsap.registerPlugin(ScrollTrigger);

// Mega menu logic
ready(() => {
    const mainHeader = document.querySelector("#masthead.main-header");
    if (!mainHeader) return;
    useMegaMenu();
    useMobileMegaMenu();
});

// Sales form logic
ready(() => {
    const salesForm = document.querySelector("#sales-form form");
    if (!salesForm) return;
    useSalesForm();
});

// Copiable buttons logic
ready(() => {
    initCopiable();
})

document.addEventListener('DOMContentLoaded', async function () {
    // Verificar si estamos en archive resources
    if (!document.body.classList.contains('post-type-archive-resources')) {
        return;
    }
    var loadmoreBtn = document.getElementById('load-more-button')
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button'); 
    const selectElement = document.getElementById('category-select');
    const selectElementType = document.getElementById('type-select');
    let page = 1;
    let loadMore = false

    // Crear una instancia de AbortController
    let fetchController = new AbortController();
    let isFetchingData = false; 
   
    
    //funcion para saber que esta seleccionado y que recursos cargar , sino es all
    function updateDataSelected() {
  
      const options = selectElement.options;
      let resourceContainer = document.getElementById('resources-container');
      for (let i = 0; i < options.length; i++) {
          options[i].setAttribute('data-selected', 'false');
      }
      const selectedOption = selectElement.options[selectElement.selectedIndex];
      const selectedOptionType = selectElementType.options[selectElementType.selectedIndex];
      selectedOption.setAttribute('data-selected', 'true');

      var postsPerPage = 12
      var canLoadMore = true
      var selectedCategory = document.getElementById('category-select').value;
      var selectedType = document.getElementById('type-select').value;
      var searchTerm = searchInput.value;
      
       // Ocultar el botón "Load More" por defecto
      document.querySelector('.more-resources-container').style.display = 'none';

      if(loadMore == false){
       
          console.log('regenera')
          resourceContainer.textContent = '';
        
        loadmoreBtn.style.display = 'inline'
        page = 1
        loadMoreResources(postsPerPage, selectedCategory, selectedType ,searchTerm, canLoadMore, loadmoreBtn);
      }else {
        page++;
        loadMoreResources(postsPerPage, selectedCategory, selectedType ,searchTerm, canLoadMore, loadmoreBtn);
      }
      console.log(searchInput)
     
    }

    // Variable para almacenar la promesa de la solicitud actual
    let currentRequestPromise = null;

   // Función para cargar recursos
   async function loadMoreResources(postsPerPage, selectedCategory, selectedType, searchTerm, canLoadMore, loadmoreBtn) {

    // Verificar si hay una solicitud en curso y abortarla si es necesario
    if (currentRequestPromise) {
        fetchController.abort();
        console.log("Solicitud anterior abortada");
    }

    // Crear una nueva instancia de AbortController
    fetchController = new AbortController();

    // Almacenar la promesa de la nueva solicitud
    const requestPromise = new Promise(async (resolve, reject) => {
        try {
           // Mostrar el indicador de carga
           document.getElementById('loader').classList.remove('hidden');

            const response = await fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                // Pasar la señal del controlador
                signal: fetchController.signal,
                body: new URLSearchParams({
                    action: 'load_more_resources',
                    page: page,
                    posts_per_page: postsPerPage,
                    category: selectedCategory,
                    search_term: searchTerm,
                    type: selectedType,
                })
            });

            if (!response.ok) {
                throw new Error('Error al cargar más recursos.');
            }

            const responseData = await response.json();
            document.getElementById('resources-container').insertAdjacentHTML('beforeend', responseData.html);
            console.log(responseData);

            if (responseData.resources_count == responseData.total_resources) {
                loadmoreBtn.style.display = 'none';
            }
            if (responseData.resources_count == null) {
                document.getElementById('resources-count').textContent = 'Showing ' + '0' + ' of ' + '0';
            } else {
                document.getElementById('resources-count').textContent = 'Showing ' + responseData.resources_count + ' of ' + responseData.total_resources;
            }

            resolve();
        } catch (error) {
            reject(error);
        } finally {
          // Ocultar el indicador de carga y mostrar el botón "Load More"
          document.getElementById('loader').classList.add('hidden');
          document.querySelector('.more-resources-container').style.display = 'block';
        }
    });

    // Asignar la promesa de la nueva solicitud como la solicitud actual
    currentRequestPromise = requestPromise;

    // Al finalizar la solicitud, borrar la referencia a la promesa de la solicitud actual
    requestPromise.finally(() => {
        currentRequestPromise = null;
        isFetchingData = false;
        console.log("Al finalizar la solicitud fetch");
        console.log("Estado de la señal de aborto:", fetchController.signal.aborted);
    });

    // Agregar manejo de errores para la promesa de la solicitud
    requestPromise.catch(error => {
        console.error(error);
    });
   }

    updateDataSelected();
     // Manejar cambios en la selección de categoría
     selectElement.addEventListener('change', function() {
      loadMore = false;
      updateDataSelected();
    });
    // Manejar cambios en la selección de tipos
    selectElementType.addEventListener('change', function() {
      loadMore = false;
      updateDataSelected();
      
    });

    // Manejar clic en el botón de búsqueda
    searchButton.addEventListener('click', function() {
        loadMore = false;
        updateDataSelected();
        
    });

    // Manejar clic en el botón "Load More"
    document.getElementById('load-more-button').addEventListener('click', function () {
      loadMore = true
      updateDataSelected();
    });
});

ready(() => {
    if (!document.body.classList.contains('blog')) return;
    useLoadMore();
    syncSectionsWithNav();
})

ready(() => {
    if (!document.body.classList.contains('single-services') && !document.body.classList.contains('single-technologies')) return;
    useServiceTags();
    useMobileServiceTags();
    useMasonry();
})

ready(() => {
    if (!document.body.classList.contains('single-resources')) return;
    useResourceLoginForm();
})

ready(() => {
    if (!document.body.classList.contains('page-template-email-verification')) return;
    useMailVerfication();
})
