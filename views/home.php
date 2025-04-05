<section class="relative bg-[url('../assets/img/estudiantes-1.jpg')] bg-cover bg-center bg-no-repeat">
    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/95 to-transparent"></div>
    <div class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8">
        <div class="max-w-xl text-left ltr:sm:text-left rtl:sm:text-right">
            <h1 class="text-3xl font-extrabold text-white sm:text-5xl">Contribuye a la Educación<strong class="block font-extrabold text-blue-600 dark:text-blue-400"> de un Estudiante </strong></h1>
            <p class="mt-4 max-w-lg text-white sm:text-xl/relaxed">Tu apoyo puede cambiar la vida de un estudiante. Juntos, podemos proporcionar los recursos necesarios para que cada uno de ellos alcance sus sueños.</p>
            <div class="mt-8 flex flex-wrap gap-4 text-left">
                <a href="#" class="inline-block text-center rounded-sm bg-blue-600 px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-blue-900 focus:ring-3 focus:outline-hidden dark:bg-blue-700 dark:hover:bg-blue-800">Donar Ahora</a>
                <a href="index.php?page=campaigns" class="inline-block text-center rounded-sm bg-white px-6 py-3 text-sm font-medium text-blue-600 shadow-sm hover:text-blue-900 focus:ring-3 focus:outline-hidden dark:bg-gray-800 dark:text-blue-400 dark:hover:text-blue-300 focus:ring-3 focus:outline-hidden">Explorar Campañas</a>
            </div>
        </div>
    </div>  
</section>

<!-- Añadir después de la sección de hero/banner -->

<section class="py-12 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">Campañas Destacadas</h2>
            <p class="text-gray-600 dark:text-gray-400">Ayuda a estos estudiantes a alcanzar sus metas educativas</p>
        </div>
        
        <div id="featured-campaigns" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Esta sección se llenará con AJAX desde un script JS o puedes cargarla directamente desde PHP -->
            <div class="text-center py-8">
                <div class="animate-pulse">
                    <div class="rounded-lg bg-gray-200 dark:bg-gray-700 h-40 mb-4"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mx-auto mb-2"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mx-auto"></div>
                </div>
                <p class="mt-4 text-gray-500 dark:text-gray-400">Cargando campañas...</p>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="index.php?page=campaigns" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                Ver Todas las Campañas
            </a>
        </div>
    </div>
</section>

<script>
// Proceso de carga de campañas destacadas 
document.addEventListener('DOMContentLoaded', function() {
    fetch('api/featured_campaigns.php?action=get_featured')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.campaigns.length > 0) {
                const container = document.getElementById('featured-campaigns');
                container.innerHTML = '';
                
                data.campaigns.forEach(campaign => {
                    // Calcular porcentaje
                    const percentage = (campaign.goal_amount > 0) 
                        ? Math.min(100, Math.round((campaign.current_amount / campaign.goal_amount) * 100)) 
                        : 0;
                    
                    // Crear elemento de campaña
                    const campaignElement = document.createElement('div');
                    campaignElement.className = 'bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow';
                    campaignElement.innerHTML = `
                        <a href="index.php?page=campaign&slug=${campaign.slug}">
                            ${campaign.campaign_image 
                                ? `<img src="${campaign.campaign_image}" alt="${campaign.title}" class="w-full h-40 object-cover">` 
                                : `<div class="w-full h-40 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                     <span class="text-gray-500 dark:text-gray-400">Sin imagen</span>
                                   </div>`
                            }
                            
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                        ${campaign.category}
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3 line-clamp-2">
                                    ${campaign.title}
                                </h3>
                                
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mb-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: ${percentage}%"></div>
                                </div>
                                
                                <div class="flex justify-between text-sm">
                                    <span class="font-semibold text-gray-800 dark:text-white">$${parseFloat(campaign.current_amount).toLocaleString('es-MX')}</span>
                                    <span class="text-gray-600 dark:text-gray-400">${percentage}% de $${parseFloat(campaign.goal_amount).toLocaleString('es-MX')}</span>
                                </div>
                            </div>
                        </a>
                    `;
                    
                    container.appendChild(campaignElement);
                });
            } else {
                document.getElementById('featured-campaigns').innerHTML = `
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">No hay campañas destacadas en este momento.</p>
                        <a href="index.php?page=create_campaign" class="inline-block mt-4 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            ¿Eres estudiante? Crea tu campaña
                        </a>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error cargando campañas destacadas:', error);
            document.getElementById('featured-campaigns').innerHTML = `
                <div class="col-span-3 text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Error al cargar campañas. Intenta nuevamente más tarde.</p>
                </div>
            `;
        });
});
</script>

<section class="bg-gray-100 py-16 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8 items-center">
            <!-- Columna izquierda -->
            <div class="w-full md:w-1/2">
                <h2 class="text-3xl font-bold text-left mb-6 dark:text-white">Nuestras Estadísticas de Impacto</h2>
                <p class="text-left mb-8 dark:text-gray-300"> 
                    Gracias a tu apoyo, hemos transformado vidas. Cada donación cuenta y
                    hace la diferencia en la educación de los estudiantes.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-center gap-6 p-4 bg-white dark:bg-gray-700 rounded-lg shadow-md">
                        <h3 class="text-4xl font-bold text-blue-600 dark:text-blue-400">120+</h3>
                        <p class="text-xl text-gray-600 dark:text-gray-300">Estudiantes ayudados</p>
                    </div>
                    
                    <div class="flex items-center gap-6 p-4 bg-white dark:bg-gray-700 rounded-lg shadow-md">
                        <h3 class="text-4xl font-bold text-blue-600 dark:text-blue-400">85+</h3>
                        <p class="text-xl text-gray-600 dark:text-gray-300">Equipos entregados</p>
                    </div>
                    
                    <div class="flex items-center gap-6 p-4 bg-white dark:bg-gray-700 rounded-lg shadow-md">
                        <h3 class="text-4xl font-bold text-blue-600 dark:text-blue-400">500+</h3>
                        <p class="text-xl text-gray-600 dark:text-gray-300">Donantes activos</p>
                    </div>
                </div>
            </div>
            
            <!-- Columna derecha -->
            <div class="w-full md:w-1/2">
                <img 
                    src="assets/img/estudiantes-2.jpg" 
                    alt="Estudiantes exitosos" 
                    class="rounded-lg shadow-xl w-full h-auto object-cover"
                />
            </div>
        </div>
    </div>
</section>

<section class="bg-white dark:bg-gray-900 py-16">
  <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
    <h2 class="text-center text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-5xl">
      Las historias de éxito de los estudiantes que usan EduFund
    </h2>

    <div class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-8">
      <blockquote class="rounded-lg bg-gray-50 dark:bg-gray-800 p-6 shadow-xs sm:p-8">
        <div class="flex items-center gap-4">
          <img
            alt=""
            src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
            class="size-14 rounded-full object-cover"
          />

          <div>
            <p class="mt-0.5 text-lg font-medium text-gray-900 dark:text-white">Juan Diego García</p>
            <div class="flex justify-center gap-0.5 text-gray-500 dark:text-gray-400">
                <p>Estudiante de Ingeniería, UNIMAR</p>
            </div>
            </div>
        </div>

        <p class="mt-4 text-gray-700 dark:text-gray-300">
        Gracias a EduFund, pude conseguir la laptop que necesitaba para mis
        estudios. Ahora puedo asistir a clases en línea sin problemas.
        </p>
      </blockquote>

      <blockquote class="rounded-lg bg-gray-50 dark:bg-gray-800 p-6 shadow-xs sm:p-8">
        <div class="flex items-center gap-4">
          <img
            alt=""
            src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
            class="size-14 rounded-full object-cover"
          />

        <div>
            <p class="mt-0.5 text-lg font-medium text-gray-900 dark:text-white">Pedro Pan</p>
            <div class="flex justify-center gap-0.5 text-gray-500 dark:text-gray-400">
                <p>Estudiante de Ingeniería, UNIMAR</p>
            </div>
            </div>
        </div>

        <p class="mt-4 text-gray-700 dark:text-gray-300">
        NAH BRO, EDUFUND GOAT 🔥🔥 Mi laptop estaba muriendo, yo en depresión, la vida nerfeándome bien feo 💀💀 abrí mi campaña y la gente empezó a soltar el cash $$$$ 🤑💸💸 en UN TOQUE ya tenía la lana pa’ mi nave nueva 💻😈 ahora TODO RÁPIDO, PURO W. EDUFUND META, EDUFUND DIOSSSS 🔛🔝 10/10, volvería a usar si la vida me da otro golpe 😤✨.
        </p>
      </blockquote>

      <blockquote class="rounded-lg bg-gray-50 dark:bg-gray-800 p-6 shadow-xs sm:p-8">
        <div class="flex items-center gap-4">
          <img
            alt=""
            src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
            class="size-14 rounded-full object-cover"
          />

        <div>
            <p class="mt-0.5 text-lg font-medium text-gray-900 dark:text-white">Jesús Rivas</p>
            <div class="flex justify-center gap-0.5 text-gray-500 dark:text-gray-400">
                <p>Estudiante de Ingeniería, UNIMAR</p>
            </div>
            </div>
        </div>

        <p class="mt-4 text-gray-700 dark:text-gray-300">
        Antes tenía una laptop que se apagaba si no deja presionada una tecla usando una liga. Gracias a EduFund, pude comprar una nueva y ahora puedo estudiar sin que Nelson me saboteé y apague mi computadora.
        </p>
      </blockquote>
    </div>
  </div>
</section>