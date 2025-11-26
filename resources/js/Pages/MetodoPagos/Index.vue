<template>
  <div class="space-y-6" :style="{ color: 'var(--color-text)' }">
    <Head title="Métodos de Pago" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">Métodos de Pago</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label :style="{ color: 'var(--color-text)' }" class="block">Buscar:</label>
        <input type="text" v-model="form.search" placeholder="Buscar métodos..." class="form-input mt-1 w-full"/>
      </search-filter>

      <select v-model="form.estado" class="form-select mr-4" :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">
        <option :value="null">Todos</option>
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
      </select>

      <Link class="btn-indigo" href="/inf513/grupo10sc/proyecto2/sis-maestranza/public/metodo-pagos/create">
        Crear Método de Pago
      </Link>
    </div>

    <div class="rounded-md shadow overflow-x-auto transition-colors" :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6" :style="{ color: 'var(--color-text)' }">Nombre</th>
            <th class="pb-4 pt-6 px-6" :style="{ color: 'var(--color-text)' }">Estado</th>
            <th class="pb-4 pt-6 px-6"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="metodo in metodos.data" :key="metodo.id" class="hover:bg-gray-100 focus-within:bg-gray-100 transition-colors" :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/inf513/grupo10sc/proyecto2/sis-maestranza/public/metodo-pagos/${metodo.id}/edit`">
                {{ metodo.nombre }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/inf513/grupo10sc/proyecto2/sis-maestranza/public/metodo-pagos/${metodo.id}/edit`" tabindex="-1">
                {{ metodo.estado }}
              </Link>
            </td>
            <td class="w-px border-t">
              <Link class="flex items-center px-4" :href="`/inf513/grupo10sc/proyecto2/sis-maestranza/public/metodo-pagos/${metodo.id}/edit`" tabindex="-1">
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>

          <tr v-if="metodos.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="3">No se encontraron métodos de pago.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination class="mt-6" :links="metodos.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues'

export default {
  components: { Head, Link, Icon, Pagination, SearchFilter },
  layout: Layout,
  props: { filters: Object, metodos: Object },
  data() {
    return {
      form: {
        search: this.filters.search,
        estado: this.filters.estado,
      }
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/inf513/grupo10sc/proyecto2/sis-maestranza/public/metodo-pagos', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() { this.form = mapValues(this.form, () => null) },
  },
}
</script>
