<template>
    <div>

        <Head title="Avances de Reserva / Cotización" />

        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Avances de Cotización</h1>
            <p class="text-gray-500 mt-1">Revisa el progreso de cada servicio realizado.</p>
        </div>

        <!-- CARD PRINCIPAL: DETALLES DE LA RESERVA -->
        <div class="bg-white shadow-xl rounded-2xl p-6 mb-10 border">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Información de la Reserva</h2>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Fecha</label>
                    <input type="date" class="form-input w-full" v-model="form.fecha" disabled>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Hora</label>
                    <input type="time" class="form-input w-full" v-model="form.hora" disabled>
                </div>
            </div>
        </div>

        <!-- SERVICIOS -->
        <div class="space-y-8">
            <div v-for="(detalle, index) in form.servicios" :key="detalle.id"
                class="bg-white shadow-xl rounded-2xl border p-6">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-indigo-700">{{ detalle.servicio.nombre }}</h2>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-6">

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Detalle del servicio</label>
                        <textarea class="form-input w-full h-24" v-model="detalle.detalle" disabled></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Precio (Bs)</label>
                        <input type="number" v-model="detalle.precio" class="form-input w-full" disabled />
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Trabajador</label>
                        <select v-model="detalle.trabajador_id" class="form-input w-full" disabled>
                            <option v-for="t in trabajadores" :key="t.id" :value="t.id">
                                {{ t.nombres }} {{ t.apellidos }}
                            </option>
                        </select>
                    </div>

                </div>

                <!-- AVANCES -->
                <div class="bg-gray-50 p-5 rounded-xl border mt-4">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">Avances Registrados</h3>
                        <button @click="abrirModal(detalle)"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-500">
                            + Agregar Avance
                        </button>
                    </div>

                    <div v-if="detalle.avances.length > 0" class="space-y-5">

                        <div v-for="avance in detalle.avances" :key="avance.id"
                            class="bg-white shadow-md border rounded-xl p-4 flex gap-4">

                            <div class="flex-1 space-y-2">
                                <p class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-500">Notas:</span>
                                    <span class="text-gray-800">{{ avance.notas }}</span>
                                </p>

                                <p class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-500">Estado:</span>
                                    <span :class="estadoClase(avance.estado_avance)" class="px-3 py-1 rounded-full text-white text-sm">
                                        {{ avance.estado_avance }}
                                    </span>
                                </p>
                            </div>

                            <div v-if="avance.foto" class="w-32 h-32 cursor-pointer">
                                <img @click="abrirImagenModal(avance.foto)"
                                    :src="`/storage/${avance.foto}`"
                                    class="w-full h-full rounded-lg object-cover shadow hover:scale-105 transition" />
                            </div>

                        </div>

                    </div>

                    <p v-else class="text-gray-500 italic">Este servicio aún no tiene avances.</p>

                </div>
            </div>
        </div>

        <!-- Total -->
        <div class="bg-white shadow-xl rounded-2xl p-6 border mt-10">
            <p class="text-xl font-semibold text-gray-800">Total: Bs {{ total }}</p>
        </div>

        <!-- Estado -->
        <div class="bg-white shadow-xl rounded-2xl p-6 border mt-6">
            <label class="block text-gray-700 font-semibold">Estado de la Reserva</label>
            <input type="text" class="form-input w-full" disabled v-model="form.estado_reserva">
        </div>

        <!-- MODAL AGREGAR AVANCE -->
        <div v-if="modal"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4 backdrop-blur-sm">

            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg">

                <h2 class="text-2xl font-bold text-gray-800 mb-6">Agregar Avance</h2>

                <div class="mb-4">
                    <label class="font-semibold text-gray-700 mb-2 block">Notas</label>
                    <textarea class="form-input w-full p-3 rounded-lg" v-model="nuevoAvance.notas" rows="4"></textarea>
                </div>

                <div class="mb-4">
                    <label class="font-semibold text-gray-700 mb-2 block">Estado</label>
                    <select class="form-input w-full p-3 rounded-lg" v-model="nuevoAvance.estado_avance">
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="EN PROCESO">En Proceso</option>
                        <option value="FINALIZADO">Finalizado</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="font-semibold text-gray-700 mb-2 block">Foto</label>
                    <input type="file" @change="subirFoto">
                    <div v-if="nuevoAvance.preview" class="mt-3">
                        <img :src="nuevoAvance.preview" class="w-40 h-40 rounded-xl object-cover shadow">
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button @click="modal=false"
                        class="px-5 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancelar
                    </button>

                    <button @click="guardarAvance"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Guardar
                    </button>
                </div>

            </div>
        </div>

        <!-- MODAL IMAGEN -->
        <div v-if="imagenModal.abierto"
            class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">

            <div class="relative bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">

                <button @click="cerrarImagenModal"
                    class="absolute top-4 right-4 bg-gray-200 w-10 h-10 rounded-full flex items-center justify-center shadow">
                    ✕
                </button>

                <div class="p-4 flex justify-center items-center w-full h-full">
                    <img :src="imagenModal.src" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow">
                </div>

            </div>
        </div>

    </div>
</template>


<script>
import { Head } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";

export default {
    props: {
        reserva: Object,
        trabajadores: Array,
        flash: Object,
    },
    layout: Layout,

    data() {
        return {
            form: {
                fecha: this.reserva.fecha,
                hora: this.reserva.hora,
                estado_reserva: this.reserva.estado_reserva,
                servicios: this.reserva.detalles.map(d => ({
                    id: d.id,
                    servicio: d.servicio,
                    detalle: d.detalles,
                    precio: Number(d.precio),
                    trabajador_id: d.trabajador_id,
                    avances: d.avances || [],
                })),
            },

            modal: false,
            detalleSeleccionado: null,

            nuevoAvance: {
                notas: "",
                estado_avance: "PENDIENTE",
                foto: null,
                preview: null,
            },

            imagenModal: {
                abierto: false,
                src: null,
            },
        };
    },

    computed: {
        total() {
            return this.form.servicios.reduce((s, x) => s + Number(x.precio), 0);
        }
    },

    methods: {
        estadoClase(estado) {
            return {
                "bg-yellow-500": estado === "PENDIENTE",
                "bg-blue-500": estado === "EN PROCESO",
                "bg-green-500": estado === "FINALIZADO",
            };
        },

        abrirModal(detalle) {
            this.detalleSeleccionado = detalle;
            this.modal = true;
        },

        subirFoto(e) {
            const file = e.target.files[0];
            this.nuevoAvance.foto = file;
            this.nuevoAvance.preview = URL.createObjectURL(file);
        },

        guardarAvance() {
            const data = new FormData();
            data.append("notas", this.nuevoAvance.notas);
            data.append("estado_avance", this.nuevoAvance.estado_avance);
            data.append("presupuesto_servicio_id", this.detalleSeleccionado.id);

            if (this.nuevoAvance.foto) {
                data.append("foto", this.nuevoAvance.foto);
            }

            this.$inertia.post("/inf513/grupo10sc/proyecto2/sis-maestranza/public/avances", data, {
                onSuccess: () => {
                    this.modal = false;
                    this.nuevoAvance = { notas: "", estado_avance: "PENDIENTE", foto: null, preview: null };
                }
            });
        },

        abrirImagenModal(foto) {
            this.imagenModal.src = `/inf513/grupo10sc/proyecto2/sis-maestranza/public/storage/${foto}`;
            this.imagenModal.abierto = true;
        },

        cerrarImagenModal() {
            this.imagenModal.abierto = false;
            this.imagenModal.src = null;
        }
    }
};
</script>

<style scoped>
.form-input {
    @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-300 focus:ring-2 outline-none;
}
</style>
