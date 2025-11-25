<template>
    <div>

        <Head title="Mis Reservas" />
        <h1 class="mb-8 text-3xl font-bold">Mis Reservas</h1>

        <div class="flex items-center justify-between mb-6">
            <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
                <label class="block text-gray-700">Buscar:</label>
                <input v-model="form.search" type="text" class="form-input mt-1 w-full"
                    placeholder="Buscar reservas..." />
            </search-filter>
        </div>

        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">ID</th>
                    <th class="pb-4 pt-6 px-6">Fecha</th>
                    <th class="pb-4 pt-6 px-6">Hora</th>
                    <th class="pb-4 pt-6 px-6">Total</th>
                    <th class="pb-4 pt-6 px-6">Estado</th>
                    <th class="pb-4 pt-6 px-6"></th>
                </tr>

                <tr v-for="reserva in reservas.data" :key="reserva.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4 focus:text-indigo-500"
                            :href="`/mis-reservas-empleado/detalle/${reserva.id}`" tabindex="-1">
                        {{ reserva.id }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4" :href="`/mis-reservas-empleado/detalle/${reserva.id}`"
                            tabindex="-1">
                        {{ reserva.fecha }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4" :href="`/mis-reservas-empleado/detalle/${reserva.id}`"
                            tabindex="-1">
                        {{ reserva.hora }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4" :href="`/mis-reservas-empleado/detalle/${reserva.id}`"
                            tabindex="-1">
                        Bs {{ reserva.total }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4" :href="`/reservas/${reserva.id}`" tabindex="-1">
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
                    <td class="w-px border-t">
                        <Link class="flex items-center px-4" :href="`/mis-reservas-empleado/detalle/${reserva.id}`"
                            tabindex="-1">
                        <Icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                        </Link>
                    </td>
                </tr>

                <tr v-if="reservas.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="6">No se encontraron reservas.</td>
                </tr>
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
                this.$inertia.get('/mis-reservas', pickBy(this.form), { preserveState: true })
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
