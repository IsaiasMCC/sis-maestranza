<template>
    <div :style="{ color: 'var(--color-text)' }">

        <Head title="Planes de Pago" />
        <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">Planes de Pago</h1>

        <div class="flex items-center justify-between mb-6">
            <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
                <label class="block" :style="{ color: 'var(--color-text)' }">Buscar:</label>
                <input v-model="form.search" type="text" class="form-input mt-1 w-full"
                    placeholder="Buscar cliente o reserva..." />
            </search-filter>
        </div>

        <div class="rounded-md shadow overflow-x-auto transition-colors"
            :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">ID</th>
                    <th class="pb-4 pt-6 px-6">Reserva</th>
                    <th class="pb-4 pt-6 px-6">Cliente</th>
                    <th class="pb-4 pt-6 px-6">Tipo de Pago</th>
                    <th class="pb-4 pt-6 px-6">Total</th>
                    <th class="pb-4 pt-6 px-6">Saldo</th>
                    <th class="pb-4 pt-6 px-6">Acciones</th>
                </tr>

                <tr v-for="plan in planes.data" :key="plan.id" class="hover:bg-gray-200 focus-within:bg-gray-200">
                    <td class="border-t px-6 py-4">{{ plan.id }}</td>
                    <td class="border-t px-6 py-4">#{{ plan.reserva.id }}</td>
                    <td class="border-t px-6 py-4">{{ plan.reserva.cliente.nombres }} {{ plan.reserva.cliente.apellidos
                        }}</td>
                    <td class="border-t px-6 py-4">{{ plan.tipo_pago }}</td>
                    <td class="border-t px-6 py-4">Bs {{ plan.total }}</td>
                    <td class="border-t px-6 py-4">Bs {{ plan.saldo }}</td>
                    <td class="border-t px-6 py-4">
                        <button @click="$inertia.get(`/inf513/grupo10sc/proyecto2/sis-maestranza/public/plan-pagos/${plan.id}`)"
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 text-sm">
                            Ver detalle
                        </button>
                    </td>

                </tr>

                <tr v-if="planes.data.length === 0">
                    <td colspan="7" class="px-6 py-4 border-t text-center text-gray-500">
                        No hay planes de pago registrados.
                    </td>
                </tr>
            </table>
        </div>

        <pagination class="mt-6" :links="planes.links" />
    </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'

export default {
    props: {
        planes: Object,
        filters: Object,
    },
    layout: Layout,
    components: {
        Head,
        Pagination,
        SearchFilter,
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
                this.$inertia.get('/inf513/grupo10sc/proyecto2/sis-maestranza/public/plan-pagos', pickBy(this.form), { preserveState: true })
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
