<template>
  <div :style="{ color: 'var(--color-text)' }">
    <Head title="Dashboard" />

    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">Dashboard</h1>

    <p class="mb-8 leading-normal" :style="{ color: 'var(--color-text)' }">
      Bienvenido al dashboard. Aquí puedes ver un resumen de tus datos.
    </p>

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div
        class="shadow rounded p-5"
        :style="{
          backgroundColor: 'var(--color-bg)',
          color: 'var(--color-text)',
        }"
      >
        <p class="text-gray-500" :style="{ color: 'var(--color-text-secondary)' }">Usuarios</p>
        <p class="text-2xl font-bold">{{ stats.users }}</p>
      </div>

      <div
        class="shadow rounded p-5"
        :style="{
          backgroundColor: 'var(--color-bg)',
          color: 'var(--color-text)',
        }"
      >
        <p class="text-gray-500" :style="{ color: 'var(--color-text-secondary)' }">Ventas</p>
        <p class="text-2xl font-bold">Bs {{ stats.sales }}</p>
      </div>

      <div
        class="shadow rounded p-5"
        :style="{
          backgroundColor: 'var(--color-bg)',
          color: 'var(--color-text)',
        }"
      >
        <p class="text-gray-500" :style="{ color: 'var(--color-text-secondary)' }">Reservas</p>
        <p class="text-2xl font-bold">{{ stats.orders }}</p>
      </div>

      <div
        class="shadow rounded p-5"
        :style="{
          backgroundColor: 'var(--color-bg)',
          color: 'var(--color-text)',
        }"
      >
        <p class="text-gray-500" :style="{ color: 'var(--color-text-secondary)' }">Pendientes</p>
        <p class="text-2xl font-bold">{{ stats.pending }}</p>
      </div>
    </div>

    <!-- Últimas reservas -->
    <div
      class="shadow rounded p-5"
      :style="{
        backgroundColor: 'var(--color-bg)',
        color: 'var(--color-text)',
      }"
    >
      <h2 class="text-xl font-bold mb-4" :style="{ color: 'var(--color-text)' }">
        Últimas reservas
      </h2>

      <table class="min-w-full divide-y" :style="{ borderColor: 'var(--color-border)' }">
        <thead>
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase" :style="{ color: 'var(--color-text-secondary)' }">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase" :style="{ color: 'var(--color-text-secondary)' }">Fecha</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase" :style="{ color: 'var(--color-text-secondary)' }">Hora</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase" :style="{ color: 'var(--color-text-secondary)' }">Total</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase" :style="{ color: 'var(--color-text-secondary)' }">Estado</th>
          </tr>
        </thead>

        <tbody :style="{ backgroundColor: 'var(--color-bg)' }" class="divide-y" :class="{'divide-gray-300': false}">
          <tr v-for="r in reservas" :key="r.id">
            <td class="px-6 py-4">{{ r.id }}</td>
            <td class="px-6 py-4">{{ r.fecha }}</td>
            <td class="px-6 py-4">{{ r.hora }}</td>
            <td class="px-6 py-4">Bs {{ r.total }}</td>

            <td class="px-6 py-4">
              <span
                :class="{
                  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                  'bg-green-100 text-green-800': r.estado_reserva === 'Completado',
                  'bg-yellow-100 text-yellow-800': r.estado_reserva === 'Pendiente',
                  'bg-red-100 text-red-800': r.estado_reserva === 'Cancelado',
                }"
              >
                {{ r.estado_reserva }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>

    </div>

  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: { Head },
  layout: Layout,

  props: {
    stats: Object,
    reservas: Array,
  },
}
</script>
