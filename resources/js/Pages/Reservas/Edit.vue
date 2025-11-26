<template>
  <div :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }" class="min-h-screen p-6">
    <Head title="Editar Reserva / Cotización" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">Editar Cotización</h1>

    <form @submit.prevent="guardarReserva" class="rounded-md shadow p-6" :style="{ backgroundColor: 'var(--color-card-bg)' }">
      <!-- Fecha y Hora -->
      <div class="mb-6">
        <label class="block font-semibold mb-2" :style="{ color: 'var(--color-text)' }">Fecha de la Reserva</label>
        <input type="date" v-model="form.fecha" class="form-input w-full" required />
      </div>

      <div class="mb-6">
        <label class="block font-semibold mb-2" :style="{ color: 'var(--color-text)' }">Hora</label>
        <input type="time" v-model="form.hora" class="form-input w-full" required />
      </div>

      <!-- Servicios Seleccionados -->
      <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4" :style="{ color: 'var(--color-text)' }">Servicios Seleccionados</h2>
        <div v-for="(detalle, index) in form.servicios" :key="detalle.id"
             class="mb-4 p-4 border rounded-md transition-colors duration-300"
             :style="{ backgroundColor: 'var(--color-bg)' }">
          <h3 class="font-semibold mb-2" :style="{ color: 'var(--color-text)' }">{{ detalle.servicio.nombre }}</h3>

          <label class="block mb-1" :style="{ color: 'var(--color-text)' }">Detalle del servicio:</label>
          <textarea v-model="detalle.detalle" class="form-input w-full" required></textarea>

          <label class="block mb-1 mt-2" :style="{ color: 'var(--color-text)' }">Precio del detalle (Bs):</label>
          <input type="number" v-model.number="detalle.precio" class="form-input w-full" min="0" required />

          <label class="block mb-1 mt-2" :style="{ color: 'var(--color-text)' }">Trabajador:</label>
          <select v-model="detalle.trabajador_id" class="form-input w-full" required>
            <option value="null" disabled>Seleccione un trabajador</option>
            <option v-for="trabajador in trabajadores" :key="trabajador.id" :value="trabajador.id">
              {{ trabajador.nombres }} {{ trabajador.apellidos }}
            </option>
          </select>
        </div>
      </div>

      <!-- Total -->
      <div class="mb-6">
        <p class="text-xl font-semibold" :style="{ color: 'var(--color-text)' }">Total: Bs {{ total }}</p>
      </div>

      <!-- Estado de la Reserva -->
      <div class="mb-6">
        <label class="block font-semibold mb-2" :style="{ color: 'var(--color-text)' }">Estado de la Reserva</label>
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
    reserva: Object,
    trabajadores: Array,
  },
  layout: Layout,
  data() {
    return {
      form: useForm({
        fecha: this.reserva.fecha,
        hora: this.reserva.hora,
        estado_reserva: this.reserva.estado_reserva,
        servicios: this.reserva.detalles.map(d => ({
          id: d.id,
          servicio: d.servicio,
          detalle: d.detalles,
          precio: parseFloat(d.precio),
          trabajador_id: d.trabajador_id || null,
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
      this.form.put(`/inf513/grupo10sc/proyecto2/sis-maestranza/public/reservas/${this.reserva.id}`)
    },
  },
}
</script>

<style scoped>
.btn-indigo {
  @apply bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-500 transition;
}
</style>
