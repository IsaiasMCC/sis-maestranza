<template>
  <div>
    <Head title="Usuarios" />
    <h1 class="mb-8 text-3xl font-bold">Usuarios</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Buscar:</label>
        <input v-model="form.search" type="text" class="form-input mt-1 w-full" placeholder="Buscar usuarios..." />
      </search-filter>

      <Link class="btn-indigo" href="/usuarios/create">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Usuario</span>
      </Link>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">CI</th>
          <th class="pb-4 pt-6 px-6">Nombres</th>
          <th class="pb-4 pt-6 px-6">Apellidos</th>
          <th class="pb-4 pt-6 px-6">Email</th>
          <th class="pb-4 pt-6 px-6">Rol</th>
          <th class="pb-4 pt-6 px-6">Estado</th>
          <th class="pb-4 pt-6 px-6"></th>
        </tr>

        <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/usuarios/${user.id}/edit`">{{ user.ci }}</Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/usuarios/${user.id}/edit`">{{ user.nombres }}</Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/usuarios/${user.id}/edit`">{{ user.apellidos }}</Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/usuarios/${user.id}/edit`">{{ user.email }}</Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/usuarios/${user.id}/edit`">{{ user.role?.name }}</Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/usuarios/${user.id}/edit`">{{ user.estado ? 'Activo' : 'Inactivo' }}</Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/usuarios/${user.id}/edit`">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>

        <tr v-if="users.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="7">Usuarios no encontrados.</td>
        </tr>
      </table>
    </div>

    <pagination class="mt-6" :links="users.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues'
import Layout from '@/Shared/Layout.vue'

export default {
  components: { Head, Icon, Link, Pagination, SearchFilter },
  layout: Layout,
  props: { filters: Object, users: Object },
  data() {
    return { form: { search: this.filters.search } }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/usuarios', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: { reset() { this.form = mapValues(this.form, () => null) } },
}
</script>
