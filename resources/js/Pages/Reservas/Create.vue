<template>
  <div class="mx-10">
    <Head title="Nueva Reserva / Cotización" />
    <h1 class="mb-8 text-3xl font-bold">Nueva Cotización</h1>

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
        <div
          v-for="(servicio, index) in form.servicios"
          :key="servicio.servicio_id"
          class="mb-4 p-4 border-4 bg-indigo-200 rounded-md"
        >
          <h3 class="font-semibold mb-2">{{ servicio.nombre }} (Bs {{ servicio.precio }})</h3>
          <label class="block text-gray-700 mb-1">Detalle del servicio:</label>
          <textarea
            v-model="servicio.detalle"
            placeholder="Describe qué quieres hacer con este servicio..."
            class="form-input w-full"
            required
          ></textarea>
        </div>
      </div>

      <!-- Total -->
      <div class="mb-6">
        <p class="text-xl font-semibold">Total: Bs {{ total }}</p>
      </div>

      <button type="submit" class="btn-indigo">Guardar Cotización</button>
    </form>
  </div>
</template>

<script>
import { Head, useForm } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  props: {
    servicios: Array,
    total: Number,
  },
  components: { Head },
  layout: Layout,
  data() {
    return {
      form: useForm({
        fecha: '',
        hora: '',
        total: this.total,
        estado_reserva: 'cotización',
        servicios: this.servicios.map((s) => ({
          servicio_id: s.id,
          nombre: s.nombre, // necesario para mostrar en el form
          detalle: '',      // el usuario lo llena
          precio: s.precio,
        })),
      }),
    }
  },
  computed: {
    total() {
      return this.total
    },
  },
  methods: {
    guardarReserva() {
      // Todo ya está en form.servicios
      this.form.post('/reservas')
    },
  },
}
</script>

<style scoped>
.btn-indigo {
  @apply bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-500 transition;
}
</style>
