<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="" content="">
    {{-- Meta de seguridad CSRF (¡MUY IMPORTANTE para formularios en Laravel!) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OSH Consulting | Servicios Profesionales En Salud Ocupacional |</title>

    {{--
      ======================================================================
      PASO 1: Mueve tu carpeta "img" completa a la carpeta "public/"
      La ruta de tu logo debe ser: public/img/osh.png
      ======================================================================
    --}}
    <link rel="icon" href="{{ asset('img/osh.png') }}">

    {{-- Los enlaces a CDNs (externos) se quedan igual --}}
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.cdnfonts.com/css/museo-500" rel="stylesheet">
    <style>
        @import url('https://fonts.cdnfonts.com/css/museo-500');
        @import url('https://fonts.cdnfonts.com/css/museo-300');

        #btn-back-to-top {
            position: fixed;
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/index.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/index.min.js"></script>

    {{--
      NOTA: El script de jQuery para el scroll suave se movió
      al final del <body>, después de que se cargue jQuery.
    --}}
</head>

<body style="font-family: 'Museo 300', sans-serif;">

<nav
    class="fixed z-20 w-full bg-white/90 backdrop-blur navbar shadow-2xl shadow-gray-600/5 border-b border-gray-100">
    <div class="container flex flex-wrap justify-between items-center mx-auto">

        {{-- ===== CORRECCIÓN DE RESPONSIVIDAD 1 ===== --}}
        {{-- Se quitaron width/height y se usó class="h-10 w-auto" --}}
        <a href="{{ route('inicio') }}" class="navbar-brand brand">
            <img src="{{ asset('img/osh_logo.png') }}" class="h-10 w-auto" alt="OSH Consulting Logo">
        </a>

        <button data-collapse-toggle="navbar-default" type="button"
                class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 "
                aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Menu</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                 xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                      d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                      clip-rule="evenodd"></path>
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul
                class="flex flex-col p-4 mt-4 transition md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0  ">
                <li>
                    {{-- Los links de ancla (#) se quedan igual --}}
                    <a href="#Sinicio"
                       class=" text-lg block py-2 pr-4 pl-3 text-black bg-gray-200 rounded md:bg-transparent md:text-blue-700 md:p-0 "
                       aria-current="page" style="font-family: 'Museo 500', sans-serif;">Inicio</a>
                </li>
                <li>
                    <button id="dropdownNavbarLink" data-dropdown-toggle="serviciosNavbar"
                            style="font-family: 'Museo 500', sans-serif;"
                            class=" flex items-center text-lg block py-2 pr-4 pl-3 text-black bg-gray-200 rounded md:bg-transparent md:text-blue-700 md:p-0 d">Servicios
                        <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg></button>
                    <!-- Dropdown menu -->
                    <div id="serviciosNavbar"
                         class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-400"
                            aria-labelledby="dropdownLargeButton">
                            <li>
                                {{--
                                  ======================================================================
                                  PASO 2: Debes crear estas rutas en routes/web.php
                                  ======================================================================
                                --}}
                                <a href="{{ route('servicios') }}"
                                   class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Servicios</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#Sproductos"
                       class=" text-lg block py-2 pr-4 pl-3 text-black bg-gray-200 rounded md:bg-transparent md:text-blue-700 md:p-0 "
                       aria-current="page" style="font-family: 'Museo 500', sans-serif;">Productos</a>
                </li>
                <li>
                    <button id="dropdownNavbarLink" data-dropdown-toggle="publicacionNavbar"
                            style="font-family: 'Museo 500', sans-serif;"
                            class=" flex items-center text-lg block py-2 pr-4 pl-3 text-black bg-gray-200 rounded md:bg-transparent md:text-blue-700 md:p-0 d">Material Informativo
                        <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg></button>
                    <!-- Dropdown menu -->
                    <div id="publicacionNavbar"
                         class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-400"
                            aria-labelledby="dropdownLargeButton">
                            <li>
                                <a href="{{ route('publicaciones') }}"
                                   class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Infografías</a>
                            </li>
                            <li>
                                {{-- Link a una ruta CON ancla --}}
                                <a href="{{ route('publicaciones') }}#Snormativas"
                                   class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Normativas</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <button id="dropdownNavbarLink" data-dropdown-toggle="acercaionNavbar"
                            style="font-family: 'Museo 500', sans-serif;"
                            class=" flex items-center text-lg block py-2 pr-4 pl-3 text-black bg-gray-200 rounded md:bg-transparent md:text-blue-700 md:p-0 d">Acerca
                        de
                        <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg></button>
                    <!-- Dropdown menu -->
                    <div id="acercaionNavbar"
                         class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-400"
                            aria-labelledby="dropdownLargeButton">
                            <li>
                                <a href="#Smyv"
                                   class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Misión
                                    y visión</a>
                            </li>
                            <li>
                                <a href="#Snv"
                                   class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Nuestros
                                    valores</a>
                            </li>
                            <li>
                                <a href="#Sclientes"
                                   class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Nuestros
                                    clientes</a>
                            </li>
                        </ul>
                        <div class="py-1">
                            <a href="#Scontacto"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">Contacto</a>
                        </div>
                        <div class="py-1">
                            {{-- Laravel Auth tiene una ruta llamada 'login' por defecto --}}
                            <a href="{{ route('login') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">Inicia Sesión</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br />
<br />
<section id="Sinicio">
    <div class="relative pt-12 bg-gray-50 sm:pt-16 lg:py-36 xl:py-48">
        <div class="absolute inset-0 hidden lg:block">
            <div class="container px-5 py-2 max-w-full lg:pt-18 lg:px-32">
                <div class="flex flex-wrap md:-m-2">
                    <div class="flex flex-wrap w-1/2">
                        <div class="w-1/2 p-1 md:p-2">
                            <img alt="gallery"
                                 class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                 src="{{ asset('img/collapse/istockphoto-1154502463-612x612.jpg') }}">
                        </div>
                        <div class="w-1/2 p-1 md:p-2">
                            <img alt="gallery"
                                 class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                 src="{{ asset('img/collapse/istockphoto-1318587171-612x612.jpg') }}">
                        </div>
                        <div class="w-full p-1 md:p-2">
                            <img alt="gallery"
                                 class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                 src="{{ asset('img/collapse/istockphoto-1320089703-612x612.jpg') }}">
                        </div>
                    </div>
                    <div class="flex flex-wrap w-1/2">
                        <div class="w-full p-1 md:p-2">
                            <img alt="gallery"
                                 class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                 src="{{ asset('img/collapse/istockphoto-1325557306-612x612.jpg') }}">
                        </div>
                        <div class="w-1/2 p-1 md:p-2">
                            <img alt="gallery"
                                 class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                 src="{{ asset('img/collapse/istockphoto-1371249255-612x612.jpg') }}">
                        </div>
                        <div class="w-1/2 p-1 md:p-2">
                            <img alt="gallery"
                                 class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                 src="{{ asset('img/collapse/istockphoto-454944167-612x612.jpg') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl items-center rounded-lg">
            <div
                class="max-w-lg mx-auto text-center lg:mx-0 lg:max-w-xs lg:text-left bg-white items-center rounded-lg">
                <a
                    class="block p-6  bg-white rounded-lg border border-gray-200 shadow-md hover:scale-110 transition duration-300 ease-in-out  items-center">

                    {{-- ===== CORRECCIÓN DE RESPONSIVIDAD 2 ===== --}}
                    {{-- Se quitó align="center" y se usó flex. Se quitaron width/height y se usó class="h-10 w-auto" --}}
                    <div class="font-normal ml-2 flex justify-center">
                        <img src="{{ asset('img/osh_logo.png') }}" class="h-10 w-auto" alt="OSH Consulting Logo">
                    </div>
                    <p class="text-center text-lg" style="font-family: 'Museo 300', sans-serif;">Servicios
                        Profesionales
                        en</p>
                    <p class="text-center text-lg" style="font-family: 'Museo 300', sans-serif;">Salud Ocupacional
                        S.A.
                        de C.V.</p>
                </a>
            </div>

            <div class="lg:hidden">
                <div class="container px-5 py-2 mx-auto lg:pt-18 lg:px-32">
                    <div class="flex flex-wrap -m-1 md:-m-2 lg:px8">
                        <div class="flex flex-wrap w-1/2">
                            <div class="w-1/2 p-1 md:p-2">
                                <img alt="gallery"
                                     class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                     src="{{ asset('img/collapse/istockphoto-1154502463-612x612.jpg') }}">
                            </div>
                            <div class="w-1/2 p-1 md:p-2">
                                <img alt="gallery"
                                     class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                     src="{{ asset('img/collapse/istockphoto-1318587171-612x612.jpg') }}">
                            </div>
                            <div class="w-full p-1 md:p-2">
                                <img alt="gallery"
                                     class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                     src="{{ asset('img/collapse/istockphoto-1320089703-612x612.jpg') }}">
                            </div>
                        </div>
                        <div class="flex flex-wrap w-1/2">
                            <div class="w-full p-1 md:p-2">
                                <img alt="gallery"
                                     class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                     src="{{ asset('img/collapse/istockphoto-1325557306-612x612.jpg') }}">
                            </div>
                            <div class="w-1/2 p-1 md:p-2">
                                <img alt="gallery"
                                     class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                     src="{{ asset('img/collapse/istockphoto-1371249255-612x612.jpg') }}">
                            </div>
                            <div class="w-1/2 p-1 md:p-2">
                                <img alt="gallery"
                                     class="block object-cover object-center w-full h-full rounded-lg hover:scale-110 transition duration-300 ease-in-out"
                                     src="{{ asset('img/collapse/istockphoto-454944167-612x612.jpg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="container flex flex-col-reverse mx-auto lg:flex-row" id="Smyv">

    <div class="text-black-500 sm:text-lg px-6 py-14" style="font-family: 'Museo 300', sans-serif;">
        <br />
        <br />
        <br />
        <br />
        <h2 class=" text-3xl tracking-tight font-extrabold text-gray-900">Misión</h2>
        <div class="mt-1">
            <span class="inline-block w-40 h-1 bg-blue-500 rounded-full"></span>
            <span class="inline-block w-3 h-1 ml-1 bg-blue-500 rounded-full"></span>
            <span class="inline-block w-1 h-1 ml-1 bg-blue-500 rounded-full"></span>
        </div>
        <p class="mb-4  mt-2 text-justify" style="font-family: 'Museo 300', sans-serif;">
            Contribuir con nuestros clientes consumidores a cuidar la salud e integridad de sus empleados,
            reduciendo las condiciones de riesgo y mejorando
            a su vez el bienestar físico, mental y social de la organización, proporcionando la
            infrestuctura y
            los
            sistemas para la promoción de la salud y control de
            accidentes y enfermedades.
        </p>
        <h2 class=" text-3xl tracking-tight font-extrabold text-gray-900">Visión</h2>
        <div class="mt-1">
            <span class="inline-block w-40 h-1 bg-blue-500 rounded-full"></span>
            <span class="inline-block w-3 h-1 ml-1 bg-blue-500 rounded-full"></span>
            <span class="inline-block w-1 h-1 ml-1 bg-blue-500 rounded-full"></span>
        </div>
        <p class="mb-4 mt-2 text-justify" style="font-family: 'Museo 300', sans-serif;">
            Ser la empresa lider en la que los consumidores confíen para temas relacionados a servicios de
            salud
            y
            trabajo, manteniendo siempre la excelencia
            operativa, trasparencia y calidad en la presentacion de nuestros servicios, garantizando así la
            satisfación de nuestros consumidores y siendo corresponsales de su
            crecimiento y desarrollo.
        </p>
        <br />
        <br />
        <br />
        <br />
    </div>
</section>

<section id="Snv">
    <br />
    <br />
    <br />
    <div class="container flex flex-col-reverse mx-auto lg:flex-row">

        <div class="flex flex-col px-6 py-4 space-y-6 rounded-sm sm:p-8 lg:p-12 lg:w-1/2 xl:w-2/5">

            <h1 class="text-2xl font-semibold text-gray-800 lg:text-3xl">Nuestros Valores</h1>
            <div class="flex space-x-2 sm:space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     class="flex-shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                    </path>
                </svg>
                <div class="space-y-2">
                    <p class="text-lg font-medium leading-snug">Innovación</p>

                </div>
            </div>
            <div class="flex space-x-2 sm:space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     class="flex-shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                    </path>
                </svg>
                <div class="space-y-2">
                    <p class="text-lg font-medium leading-snug">Integridad</p>

                </div>
            </div>
            <div class="flex space-x-2 sm:space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     class="flex-shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                    </path>
                </svg>
                <div class="space-y-2">
                    <p class="text-lg font-medium leading-snug">Dinamismo</p>

                </div>
            </div>
            <div class="flex space-x-2 sm:space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     class="flex-shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                    </path>
                </svg>
                <div class="space-y-2">
                    <p class="text-lg font-medium leading-snug">Transparencia</p>

                </div>
            </div>
            <div class="flex space-x-2 sm:space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     class="flex-shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                    </path>
                </svg>
                <div class="space-y-2">
                    <p class="text-lg font-medium leading-snug">Ética profesional</p>

                </div>
            </div>
            <div class="flex space-x-2 sm:space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     class="flex-shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                    </path>
                </svg>
                <div class="space-y-2">
                    <p class="text-lg font-medium leading-snug">Responsabilidad</p>

                </div>
            </div>
            <div class="flex space-x-2 sm:space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     class="flex-shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                    </path>
                </svg>
                <div class="space-y-2">
                    <p class="text-lg font-medium leading-snug">Compromiso social</p>

                </div>
            </div>
        </div>
        <div class="lg:w-1/2 xl:w-3/5">
            <div class="flex items-center justify-center p-4 md:p-8 lg:p-12">
                {{-- Esta imagen ya era responsiva --}}
                <img src="{{ asset('img/collapse/istockphoto-1154502463-612x612.jpg') }}" alt=""
                     class="w-full rounded-lg shadow-lg dark:bg-gray-500 aspect-video sm:min-h-96">
            </div>
        </div>
    </div>
    <br />
    <br />
    <br />
    <br />
    <br />
</section>
<section class="bg-white " id="Sproductos">
    <br />
    <br />
    <br />
    <div class="container px-6 py-6 mx-auto" style="font-family: 'Museo 300', sans-serif;">
        <h1 class="text-2xl font-semibold text-gray-800 lg:text-3xl">Productos y <br>Servicios</h1>

        <div class="mt-2">
            <span class="inline-block w-40 h-1 bg-blue-500 rounded-full"></span>
            <span class="inline-block w-3 h-1 ml-1 bg-blue-500 rounded-full"></span>
            <span class="inline-block w-1 h-1 ml-1 bg-blue-500 rounded-full"></span>
        </div>

        <div class="mt-8 xl:mt-12 lg:flex lg:items-center" style="font-family: 'Museo 300', sans-serif;">
            <div class="hidden lg:flex lg:w-1/3 lg:justify-center mix-blend-multiply">
                {{-- Esta imagen ya era responsiva (w-4/5) --}}
                <img class="w-4/5 rounded-lg" src="{{ asset('img/pexels-thirdman-8469988.jpg') }}" alt="office content 1">
            </div>
            <div class="grid w-full grid-cols-1 gap-8 lg:w-3/5 xl:gap-16 md:grid-cols-2 mix-blend-multiply">
                <div class="space-y-3">
                        <span class="inline-block p-3 text-blue-500 bg-blue-100 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </span>

                    <h1 class="text-xl font-semibold text-gray-900 capitalize">Asesoriá</h1>

                    <p class="text-black-500 sm:text-lg text-justify">
                        Brindamos asesoria en temas de Salud, Seguridad e Higiene Laboral.
                    </p>
                </div>

                <div class="space-y-3">
                        <span class="inline-block p-3 text-blue-500 bg-blue-100 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                            </svg>
                        </span>

                    <h1 class="text-xl font-semibold text-gray-900 capitalize">Suministros</h1>

                    <p class="text-black-500 sm:text-lg text-justify">
                        Pruebas de antígeno y antidoping; medicamento; material de curación; equipo médico para
                        consulturio, de seguridad, potección y relacionado al cumplimiento de la calidad.
                    </p>
                </div>

                <div class="space-y-3">
                        <span class="inline-block p-3 text-blue-500 bg-blue-100 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                            </svg>
                        </span>

                    <h1 class="text-xl font-semibold text-gray-900 capitalize">Cursos y capacitaciones</h1>

                    <p class="text-black-500 sm:text-lg text-justify">
                        NOM-30-STPS-2009, NOM-035-2018, legislación en salud ocupacional, Evaluación de riesgos,
                        ergonomía y demás relacionados a la seguridad, salud e higiene laboral.
                    </p>
                </div>

                <div class="space-y-3">
                        <span class="inline-block p-3 text-blue-500 bg-blue-100 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </span>

                    <h1 class="text-xl font-semibold text-gray-900 capitalize">Otros</h1>

                    <p class="text-black-500 sm:text-lg text-justify">
                        Sistemas de gestión, Servicios médicos a demanda, Exámenes médicos por puesto de trabajo
                    </p>
                </div>
            </div>


        </div>
    </div>
</section>
<section id="Sclientes">
    <br/>
    <br/>
    <br/>
    <hr class="my-12 border-gray-200">

    {{-- Esta sección ya era responsiva --}}
    <div class="grid grid-cols-2 gap-8 md:grid-cols-6 lg:grid-cols-5 bg-white">
        <div class="flex items-center justify-center col-span-1 md:col-span-2 lg:col-span-1">
            <img class="w-full max-w-[180px] h-auto text-gray-500 fill-current grayscale" src="{{ asset('img/empresa/sintenovo_c.jpg') }}" alt="Logo Sintenovo">
        </div>

        <div class="flex items-center justify-center col-span-1 md:col-span-2 lg:col-span-1">
            <img class="w-full max-w-[180px] h-auto text-gray-500 fill-current grayscale" src="{{ asset('img/empresa/ARCOSA_500X216.jpg') }}" alt="Logo Arcosa">
        </div>

        <div class="flex items-center justify-center col-span-1 md:col-span-2 lg:col-span-1">
            <img class="w-full max-w-[180px] h-auto text-gray-500 fill-current grayscale" src="{{ asset('img/empresa/Allergan_Logo.png') }}" alt="Logo Allergan">
        </div>

        <div class="flex items-center justify-center col-span-1 md:col-span-3 lg:col-span-1">
            <img class="w-full max-w-[180px] h-auto text-gray-500 fill-current grayscale" src="{{ asset('img/empresa/Logo-bureau-véritas.png') }}" alt="Logo Bureau Veritas">
        </div>

        <div class="flex items-center justify-center col-span-2 md:col-span-3 lg:col-span-1">
            <img class="w-full max-w-[180px] h-auto text-gray-500 fill-current grayscale" src="{{ asset('img/empresa/genoma_c.png') }}" alt="Logo Genoma">
        </div>
    </div>
</section>


<hr class="my-12 border-gray-200">
<br />
<br />
<br />
<div class="max-w-full pt-4" id="Scontacto">
    <section class="py-6">
        <br/>
        <br/>
        <br/>
        <div class="grid max-w-6xl grid-cols-1 px-6 mx-auto lg:px-10 md:grid-cols-2 md:divide-x">
            <div class="py-6 md:py-0 md:px-6">
                <h1 class="text-4xl font-bold">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">Ponte en contacto</font>
                    </font>
                </h1>
                <p class="pt-2 pb-4">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">Complete el formulario para iniciar una conversación.
                        </font>
                    </font>
                </p>
                <div class="space-y-4">

                    <p class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 mr-2 sm:mr-6">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        <span>
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">contacto@oshconsulting.com.mx</font>
                                </font>
                            </span>
                    </p>
                </div>
            </div>

            {{--
              ======================================================================
              PASO 3: El formulario no necesita 'action' ni 'method'
              porque lo maneja JavaScript.
              Asegúrate de que la ruta 'enviar.correo' exista.
              ======================================================================
            --}}
            <form novalidate=""
                  class="flex flex-col py-6 space-y-6 md:py-0 md:px-6 ng-untouched ng-pristine ng-valid">

                {{-- No necesitas un @csrf aquí si lo envías con JS, pero sí en el <head> --}}

                <label class="block">
                        <span class="mb-1">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">Nombre completo</font>
                            </font>
                        </span>
                    <input type="text" placeholder="Leroy Jenkins" id="modalNombreS"
                           class="block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-75 focus:ring-blue-400 ">
                </label>
                <label class="block">
                        <span class="mb-1">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">Telefono</font>
                            </font>
                        </span>
                    <input type="number" placeholder="555 556 666" id="modalTelS"
                           class="block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-75 focus:ring-blue-400 ">
                </label>
                <label class="block">
                        <span class="mb-1">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">Dirección de correo electrónico</font>
                            </font>
                        </span>
                    <input type="email" placeholder="username@gmail.com" id="modalEmailS"
                           class="block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-75 focus:ring-blue-400 ">
                </label>
                <label class="block">
                        <span class="mb-1">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">Razón social</font>
                            </font>
                        </span>
                    <input type="text" placeholder="IT OSH CONSULTING" id="modalRazonS"
                           class="block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-75 focus:ring-blue-400 ">
                </label>
                <label class="block">
                        <span class="mb-1">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">Mensaje</font>
                            </font>
                        </span>
                    <textarea rows="3" id="modalMensajeS" placeholder="Me interesa el siguiente producto..."
                              class="block w-full rounded-md focus:ring focus:ring-opacity-75 focus:ring-blue-400"></textarea>
                </label>
                <div align="center">
                    <button id="enviarE" onclick="enviarCorreo()" type="button"
                            class="self-center px-8 py-3 text-lg bg-blue-500 text-white rounded focus:ring hover:ring focus:ring-opacity-75 focus:ring-blue-400 hover:ring-blue-400">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">Enviar </font>
                        </font>
                    </button>
                </div>
                <button id="cargarE" type="button"
                        class="self-center px-8 py-3 text-lg bg-blue-500 text-white rounded focus:ring hover:ring focus:ring-opacity-75 focus:ring-blue-400 hover:ring-blue-400">
                    <svg aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
                         viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="#E5E7EB" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentColor" />
                    </svg>
                    Enviando...
                </button>
            </form>
        </div>
    </section>
</div>





<footer class="bg-white rounded-lg shadow md:px-6 md:py-8 dark:bg-gray-900">

        <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400"
              style="font-family: 'Museo 300', sans-serif;">© 2023 OSH Consulting. All Rights Reserved.
        </span>
</footer>

<button type="button" data-mdb-ripple="true" data-mdb-ripple-color="light"
        class="inline-block p-3 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded-full shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out bottom-5 right-5"
        id="btn-back-to-top">
    <svg aria-hidden="true" focusable="false" data-prefix="fas" class="w-4 h-4" role="img"
         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
        <path fill="currentColor"
              d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z">
        </path>
    </svg>
</button>

</body>

</html>

<!-- SCRIPTS =============================-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css"
      href="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.js"></script>

<script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
{{-- El script local "../path/to/flowbite/dist/flowbite.js" se eliminó porque era una ruta rota y ya se carga desde el CDN --}}

{{-- Script de jQuery para scroll suave (MOVIDO DESDE EL <head>) --}}
<script>
    $(document).ready(function () {
        $("a").click(function () {
            var pageId = $(this).attr("data-page");
            if (pageId) { // Asegurarse de que pageId no sea undefined
                $("html, body").animate({
                    scrollTop: $("#" + pageId).offset().top
                }, 1000);
            }
        });
    });
</script>

{{-- Script para el botón "Back to Top" (sin cambios) --}}
<script>
    // Get the button
    let mybutton = document.getElementById("btn-back-to-top");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {
        if (
            document.body.scrollTop > 20 ||
            document.documentElement.scrollTop > 20
        ) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }
    // When the user clicks on the button, scroll to the top of the document
    mybutton.addEventListener("click", backToTop);

    function backToTop() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>

{{--
  ======================================================================
  PASO 4: Script de envío de correo ACTUALIZADO para Laravel
  ======================================================================
--}}
<script>
    var cargarE = document.querySelector('#cargarE');
    var enviarE = document.querySelector('#enviarE');
    cargarE.style.display = "none";

    function enviarCorreo() {
        cargarE.style.display = "block";
        enviarE.style.display = "none";

        // Obtenemos el token CSRF del meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        modalNombreS = document.getElementById('modalNombreS').value;
        modalTelS = document.getElementById('modalTelS').value;
        modalEmail = document.getElementById('modalEmailS').value;
        modalRazonS = document.getElementById('modalRazonS').value;
        modalMensaje = document.getElementById("modalMensajeS").value;
        idP = '';

        var data = new FormData();
        data.append("idNombre", modalNombreS);
        data.append("idTelefono", modalTelS);
        data.append("idCorreo", modalEmail);
        data.append("idRz", modalRazonS);
        data.append("idMensaje", modalMensaje);
        data.append("idP", idP);

        // CAMBIO 1: La URL ahora usa la ruta de Laravel
        fetch('{{ route("enviar.correo") }}', {
            method: 'POST',
            headers: {
                // CAMBIO 2: Añadimos el token CSRF al header
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: data
        })
            .then(function (response) {
                if (response.ok) {
                    return response.text()
                } else {
                    throw "Error en la llamada Ajax";
                }

            })
            .then(function (texto) {
                cargarE.style.display = "none";
                enviarE.style.display = "block";
                $.jGrowl("Correo enviado", {
                    life: 2000
                });
                console.log(texto);
                // Limpiar el formulario (opcional)
                document.getElementById('modalNombreS').value = '';
                document.getElementById('modalTelS').value = '';
                document.getElementById('modalEmailS').value = '';
                document.getElementById('modalRazonS').value = '';
                document.getElementById('modalMensajeS').value = '';
            })
            .catch(function (err) {
                console.log(err);
                cargarE.style.display = "none";
                enviarE.style.display = "block";
                $.jGrowl("Error al enviar el correo", {
                    header: 'Error',
                    theme: 'bg-red-500',
                    life: 3000
                });
            });
        console.log(data);
    }
</script>

