<template>
    <div class="mx-10">

        <Head title="Servicios" />
        <h1 class="mb-8 text-3xl font-bold">Servicios</h1>

        <div class="flex items-center justify-between mb-6">
            <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
                <label class="block text-gray-700">Buscar:</label>
                <input v-model="form.search" type="text" class="form-input mt-1 w-full"
                    placeholder="Buscar servicios..." />
            </search-filter>
        </div>
    </div>
    <div class="px-4 md:px-8 flex flex-col lg:flex-row items-start gap-8">
        <!-- Servicios -->
        <div class="flex-1">


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div v-for="servicio in serviciosFiltrados" :key="servicio.id"
                    class="bg-white rounded-md shadow p-6 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-bold mb-2">{{ servicio.nombre }}</h2>
                        <p class="text-gray-600 mb-4">{{ servicio.detalles }}</p>
                        <p class="text-indigo-600 font-semibold mb-4">Bs {{ servicio.precio }}</p>
                    </div>
                    <button class="btn-indigo w-full mt-auto" @click="agregarACarrito(servicio)">
                        Agregar al Carrito
                    </button>
                </div>
            </div>
        </div>

        <!-- Carrito -->
        <div v-if="carrito.length" class="w-full lg:w-96 bg-white rounded-md shadow p-6 mt-6 lg:mt-0">
            <h2 class="text-2xl font-bold mb-4">Cotización</h2>
            <ul class="mb-4">
                <li v-for="item in carrito" :key="item.id" class="flex justify-between items-center mb-2">
                    <span class="flex-1">{{ item.nombre }}</span>
                    <span class="w-20 text-right">Bs {{ item.precio }}</span>
                    <button class="text-red-600 ml-4" @click="quitarDelCarrito(item)">Eliminar</button>
                </li>
            </ul>
            <p class="text-xl font-semibold mb-4">Total: Bs {{ total }}</p>
            <button class="btn-indigo w-full" @click="generarCotizacion">Generar Cotización</button>
        </div>
    </div>
</template>


<script>
import { Head } from '@inertiajs/vue3'
import SearchFilter from '@/Shared/SearchFilter.vue'
import { useForm } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
    components: { Head, SearchFilter },
    props: {
        servicios: Array,
        filters: Object,
    },
    layout: Layout,
    data() {
        return {
            form: useForm({
                search: this.filters.search || '',
            }),
            carrito: [],
        }
    },
    computed: {
        serviciosFiltrados() {
            if (!this.form.search) return this.servicios
            return this.servicios.filter(s =>
                s.nombre.toLowerCase().includes(this.form.search.toLowerCase())
            )
        },
        total() {
            return this.carrito.reduce((sum, item) => sum + Number(item.precio), 0)
        },
    },
    watch: {
        'form.search': {
            handler: function () {
                this.$inertia.get('/servicios', { search: this.form.search }, { preserveState: true })
            },
            deep: true,
        },
    },
    methods: {
        reset() { this.form.search = '' },
        agregarACarrito(servicio) {
            if (!this.carrito.some(s => s.id === servicio.id)) {
                this.carrito.push(servicio)
            }
        },
        quitarDelCarrito(servicio) {
            this.carrito = this.carrito.filter(s => s.id !== servicio.id)
        },
        generarCotizacion() {
            this.$inertia.get('/reservas/create', {
                total: this.total,
                servicios: JSON.stringify(this.carrito),
            });
        },
    },
}
</script>

<style scoped>
.btn-indigo {
    @apply bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-500 transition;
}
</style>
