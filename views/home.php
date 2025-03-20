<section class="hero bg-gradient-to-r from-blue-500 to-purple-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-6">Ayuda a estudiantes a cumplir sus sueños educativos</h1>
        <p class="text-xl mb-8">Plataforma de crowdfunding especializada en apoyar a estudiantes que necesitan recursos para continuar su educación</p>
        <div class="flex justify-center space-x-4">
            <a href="#" class="bg-white text-blue-600 hover:bg-blue-100 px-6 py-3 rounded-lg font-bold">Donar ahora</a>
            <a href="#" class="bg-transparent border-2 border-white hover:bg-white hover:text-blue-600 px-6 py-3 rounded-lg font-bold">Crear campaña</a>
        </div>
    </div>
</section>

<section class="featured-campaigns py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-10">Campañas destacadas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Marcadores de posición para campañas -->
            <?php for ($i = 1; $i <= 3; $i++) : ?>
                <div class="campaign-card border rounded-lg overflow-hidden shadow-lg">
                    <img src="https://via.placeholder.com/600x300" alt="Campaña" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Ayúdame a comprar una laptop para mis estudios</h3>
                        <p class="text-gray-700 mb-4">Soy estudiante de ingeniería y necesito una computadora para continuar mis estudios...</p>
                        <div class="mb-4">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 65%"></div>
                            </div>
                            <div class="flex justify-between mt-2 text-sm">
                                <span>$650 recaudados</span>
                                <span>Meta: $1,000</span>
                            </div>
                        </div>
                        <a href="#" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ver detalles</a>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        <div class="text-center mt-10">
            <a href="#" class="inline-block border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-bold hover:bg-blue-600 hover:text-white">Ver más campañas</a>
        </div>
    </div>
</section>

<section class="stats bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="stat p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-4xl font-bold text-blue-600">120+</h3>
                <p class="text-xl text-gray-600">Estudiantes ayudados</p>
            </div>
            <div class="stat p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-4xl font-bold text-blue-600">85+</h3>
                <p class="text-xl text-gray-600">Equipos entregados</p>
            </div>
            <div class="stat p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-4xl font-bold text-blue-600">500+</h3>
                <p class="text-xl text-gray-600">Donantes activos</p>
            </div>
        </div>
    </div>
</section>