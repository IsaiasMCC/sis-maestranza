<template>
  <div :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }" class="min-h-screen p-6">
    <Head title="Mis Reservas" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">Mis Reservas</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mb-1" :style="{ color: 'var(--color-text)' }">Buscar:</label>
        <input v-model="form.search" type="text" class="form-input mt-1 w-full" placeholder="Buscar reservas..." />
      </search-filter>
    </div>

    <div class="rounded-md shadow overflow-x-auto transition-colors duration-300"
         :style="{ backgroundColor: 'var(--color-card-bg)' }">
      <table class="w-full whitespace-nowrap transition-colors duration-300">
        <thead>
          <tr class="text-left font-bold" :style="{ color: 'var(--color-text)' }">
            <th class="pb-4 pt-6 px-6">ID</th>
            <th class="pb-4 pt-6 px-6">Fecha</th>
            <th class="pb-4 pt-6 px-6">Hora</th>
            <th class="pb-4 pt-6 px-6">Total</th>
            <th class="pb-4 pt-6 px-6">Estado</th>
            <th class="pb-4 pt-6 px-6"></th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="reserva in reservas.data" :key="reserva.id"
              class="border-t transition-colors duration-300 hover:bg-[var(--color-hover)]">
            <td class="px-6 py-4">
              <Link :href="`/inf513/grupo10sc/proyecto2/sis-maestranza/public/reservas/${reserva.id}/edit`"
                    class="flex items-center" :style="{ color: 'var(--color-text)' }">
                {{ reserva.id }}
              </Link>
            </td>
            <td class="px-6 py-4">
              <Link :href="`/inf513/grupo10sc/proyecto2/sis-maestranza/public/mis-reservas/detalle/${reserva.id}`"
                    class="flex items-center" tabindex="-1" :style="{ color: 'var(--color-text)' }">
                {{ reserva.fecha }}
              </Link>
            </td>
            <td class="px-6 py-4">
              <Link :href="`/inf513/grupo10sc/proyecto2/sis-maestranza/public/mis-reservas/detalle/${reserva.id}`"
                    class="flex items-center" tabindex="-1" :style="{ color: 'var(--color-text)' }">
                {{ reserva.hora }}
              </Link>
            </td>
            <td class="px-6 py-4">
              <Link :href="`/inf513/grupo10sc/proyecto2/sis-maestranza/public/mis-reservas/detalle/${reserva.id}`"
                    class="flex items-center" tabindex="-1" :style="{ color: 'var(--color-text)' }">
                Bs {{ reserva.total }}
              </Link>
            </td>
            <td class="px-6 py-4">
              <Link :href="`/inf513/grupo10sc/proyecto2/sis-maestranza/public/reservas/${reserva.id}/edit`"
                    class="flex items-center" tabindex="-1">
                <span :class="{
                  'px-2 py-1 rounded-full text-white text-sm font-semibold': true,
                  'bg-yellow-500': reserva.estado_reserva === 'PENDIENTE',
                  'bg-green-500': reserva.estado_reserva === 'CONFIRMADA',
                  'bg-blue-500': reserva.estado_reserva === 'FINALIZADA',
                  'bg-red-500': reserva.estado_reserva === 'CANCELADA',
                }">
                  {{ reserva.estado_reserva }}
                </span>
              </Link>
            </td>
            <td class="w-px px-4 py-4">
              <Link :href="`/inf513/grupo10sc/proyecto2/sis-maestranza/public/mis-reservas/detalle/${reserva.id}`"
                    class="flex items-center" tabindex="-1">
                <Icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>

          <tr v-if="reservas.data.length === 0">
            <td class="px-6 py-4 border-t text-center" colspan="6" :style="{ color: 'var(--color-text)' }">
              No se encontraron reservas.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination class="mt-6" :links="reservas.links" />
  </div>
</template>


<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    reservas: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/inf513/grupo10sc/proyecto2/sis-maestranza/public/mis-reservas', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
