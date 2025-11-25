<template>
    <div>

        <Head title="Editar Reserva / Cotización" />
        <h1 class="mb-8 text-3xl font-bold">Editar Cotización</h1>

        <form @submit.prevent="guardarReserva">
            <!-- Fecha y Hora -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Fecha de la Reserva</label>
                <input type="date" v-model="form.fecha" class="form-input w-full" required />
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Hora</label>
                <input type="time" v-model="form.hora" class="form-input w-full" required />
            </div>

            <!-- Servicios Seleccionados -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-4">Servicios Seleccionados</h2>
                <div v-for="(detalle, index) in form.servicios" :key="detalle.id" class="mb-4 p-4 border rounded-md">
                    <h3 class="font-semibold mb-2">{{ detalle.servicio.nombre }}</h3>

                    <label class="block text-gray-700 mb-1">Detalle del servicio:</label>
                    <textarea v-model="detalle.detalle" class="form-input w-full" required></textarea>

                    <label class="block text-gray-700 mb-1 mt-2">Precio del detalle (Bs):</label>
                    <input type="number" v-model.number="detalle.precio" class="form-input w-full" min="0" required />

                    <label class="block text-gray-700 mb-1 mt-2">Trabajador:</label>
                    <select v-model="detalle.trabajador_id" class="form-input w-full" required>
                        <option value="null" disabled> Seleccione un trabajador</option>
                        <option v-for="trabajador in trabajadores" :key="trabajador.id" :value="trabajador.id">
                            {{ trabajador.nombres }} {{ trabajador.apellidos }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Total -->
            <div class="mb-6">
                <p class="text-xl font-semibold">Total: Bs {{ total }}</p>
            </div>

            <!-- Estado de la Reserva -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Estado de la Reserva</label>
                <select v-model="form.estado_reserva" class="form-input w-full" required>
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="CONFIRMADA">Confirmada</option>
                    <option value="FINALIZADA">Finalizada</option>
                    <option value="CANCELADA">Cancelada</option>
                </select>
            </div>


            <button type="submit" class="btn-indigo">Actualizar Cotización</button>
        </form>
    </div>
</template>

<script>
import { Head, useForm } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
    props: {
        reserva: Object, // Reserva con detalles y servicios cargados
        trabajadores: Array, // Lista de trabajadores disponibles
    },
    layout: Layout,
    data() {
        return {
            form: useForm({
                fecha: this.reserva.fecha,
                hora: this.reserva.hora,
                estado_reserva: this.reserva.estado_reserva,
                servicios: this.reserva.detalles.map(d => ({
                    id: d.id, // id del detalle
                    servicio: d.servicio, // relación Servicio
                    detalle: d.detalles,
                    precio: parseFloat(d.precio),
                    trabajador_id: d.trabajador_id || null, // trabajador asignado
                })),
            }),
        }
    },
    computed: {
        total() {
            return this.form.servicios.reduce((sum, s) => sum + Number(s.precio), 0)
        },
    },
    methods: {
        guardarReserva() {
            this.form.put(`/reservas/${this.reserva.id}`)
        },
    },
}
</script>

<style scoped>
.btn-indigo {
    @apply bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-500 transition;
}
</style>
