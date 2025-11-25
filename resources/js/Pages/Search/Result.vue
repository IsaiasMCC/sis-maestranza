<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Resultados para "{{ query }}"</h1>

    <div v-if="users.length">
      <h2 class="font-semibold mb-2">Usuarios</h2>
      <ul class="mb-4">
        <li v-for="u in users" :key="u.id">{{ u.name }} ({{ u.email }})</li>
      </ul>
    </div>

    <div v-if="reservas.length">
      <h2 class="font-semibold mb-2">Reservas</h2>
      <ul class="mb-4">
        <li v-for="r in reservas" :key="r.id">
          ID: {{ r.id }} - Estado: {{ r.estado_reserva }}
        </li>
      </ul>
    </div>

    <div v-if="ventas.length">
      <h2 class="font-semibold mb-2">Ventas</h2>
      <ul>
        <li v-for="v in ventas" :key="v.id">
          Total: Bs {{ v.total }}
        </li>
      </ul>
    </div>

    <div v-if="!users.length && !reservas.length && !ventas.length">
      <p>No se encontraron resultados.</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    query: String,
    users: Array,
    reservas: Array,
    ventas: Array,
  },
}
</script>
