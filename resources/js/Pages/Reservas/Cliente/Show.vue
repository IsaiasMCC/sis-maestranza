<template>
  <div>

    <Head title="Detalle de Reserva / Cotización" />

    <h1 class="mb-8 text-3xl font-bold text-gray-800">Detalle de Cotización</h1>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="bg-white border shadow-md rounded-xl p-6 space-y-6">

      <!-- Fecha -->
      <div>
        <label class="text-gray-700 font-semibold mb-1 block">Fecha de la Reserva</label>
        <p class="px-4 py-2 bg-gray-100 rounded-md border">{{ reserva.fecha }}</p>
      </div>

      <!-- Hora -->
      <div>
        <label class="text-gray-700 font-semibold mb-1 block">Hora</label>
        <p class="px-4 py-2 bg-gray-100 rounded-md border">{{ reserva.hora }}</p>
      </div>

      <!-- Servicios -->
      <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Servicios Seleccionados</h2>

        <div v-for="detalle in reserva.detalles" :key="detalle.id"
          class="p-4 bg-gray-50 rounded-lg border shadow-sm mb-4">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">
            {{ detalle.servicio.nombre }}
          </h3>

          <div class="space-y-3">

            <div>
              <label class="font-semibold text-gray-700 block">Detalle del servicio:</label>
              <p class="mt-1 bg-gray-100 p-2 rounded border">{{ detalle.detalles }}</p>
            </div>

            <div>
              <label class="font-semibold text-gray-700 block">Precio (Bs):</label>
              <p class="mt-1 bg-gray-100 p-2 rounded border">{{ detalle.precio }}</p>
            </div>

            <div>
              <label class="font-semibold text-gray-700 block">Trabajador:</label>
              <p class="mt-1 bg-gray-100 p-2 rounded border">
                {{ detalle.trabajador?.nombres }} {{ detalle.trabajador?.apellidos }}
              </p>
            </div>

          </div>
        </div>
      </div>

      <!-- Total -->
      <div>
        <p class="text-xl font-bold text-gray-800">
          Total: Bs {{ totalCalculado }}
        </p>
      </div>

      <!-- Estado -->
      <div class="flex items-center ">
        <label class="block text-gray-700 font-semibold mb-2">Estado de la Reserva</label>
        <span class="px-4 ml-3 py-2 rounded-full text-white font-semibold" :class="{
          'bg-yellow-500': reserva.estado_reserva === 'PENDIENTE',
          'bg-green-500': reserva.estado_reserva === 'CONFIRMADA',
          'bg-blue-500': reserva.estado_reserva === 'FINALIZADA',
          'bg-red-500': reserva.estado_reserva === 'CANCELADA',
        }">
          {{ reserva.estado_reserva }}
        </span>
      </div>

      <!-- ===================== PLAN DE PAGO ===================== -->
      <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Forma de Pago</h2>

        <!-- Si no hay plan de pago, mostrar formulario -->
        <div v-if="!reserva.plan_pago" class="p-5 bg-gray-50 rounded-lg border shadow">

          <!-- Tipo de Pago -->
          <label class="font-semibold text-gray-700 block mb-1">Tipo de Pago</label>
          <select v-model="pago.tipo_pago" class="form-input w-full border rounded-lg p-2 mb-4">
            <option value="" disabled>Seleccione...</option>
            <option value="CONTADO">Al Contado</option>
            <option value="CREDITO">Al Crédito</option>
          </select>

          <!-- Total calculado -->
          <label class="font-semibold text-gray-700 block mb-1">Total (Bs)</label>
          <p class="p-2 rounded border bg-gray-100">{{reserva.detalles.reduce((sum, d) => sum + Number(d.precio), 0)}}
          </p>

          <button @click="guardarPlanPago"
            class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-500">
            Guardar Pago
          </button>
        </div>

        <!-- Si ya existe plan de pago -->
        <div v-else
          class="p-5 bg-white rounded-lg border shadow flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
          <div class="space-y-2">
            <p class="font-semibold text-gray-700">
              <span class="text-gray-500">Tipo de Pago:</span> {{ reserva.plan_pago.tipo_pago }}
            </p>
            <p class="font-semibold text-gray-700">
              <span class="text-gray-500">Total:</span> Bs {{ reserva.plan_pago.total }}
            </p>
            <p v-if="reserva.plan_pago.tipo_pago === 'CREDITO'" class="font-semibold text-gray-700">
              <span class="text-gray-500">Saldo:</span> Bs {{ reserva.plan_pago.saldo }}
            </p>
          </div>

          <button @click="$inertia.get(`/inf513/grupo10sc/proyecto2/sis-maestranza/public/plan-pagos/${reserva.plan_pago.id}/pagar`)"
            class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-500 transition duration-150">
            💳 Pagar
          </button>
        </div>

      </div>


    </div>

    <!-- ===================== TICKETS ===================== -->
    <div class="mt-10">
      <h2 class="text-2xl font-bold mb-4 text-gray-800">Tickets de Soporte</h2>

      <button @click="abrirModalTicket"
        class="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-500 transition mb-4">
        Solicitar Ticket
      </button>

      <div v-if="reserva.tickets && reserva.tickets.length" class="space-y-3">
        <div v-for="ticket in reserva.tickets" :key="ticket.id" class="border p-4 rounded-lg bg-white shadow">
          <div class="flex justify-between items-center mb-2">
            <p class="font-semibold text-gray-800">
              Ticket #{{ ticket.nro }} — Estado: {{ ticket.estado_ticket }}
            </p>

            <button @click="abrirChat(ticket)"
              class="px-3 py-1 bg-blue-600 text-white rounded text-sm shadow hover:bg-blue-500 transition">
              {{ ticket.chatAbierto ? "Cerrar Chat" : "Abrir Chat" }}
            </button>
          </div>

          <!-- Chat -->
          <div v-if="ticket.chatAbierto"
            class="bg-gray-50 border p-3 rounded-lg shadow-inner max-h-64 overflow-y-auto space-y-2">
            <div v-for="mensaje in ticket.mensajes" :key="mensaje.id"
              :class="{ 'text-right': mensaje.empleado_id == userId }">
              <span class="inline-block px-3 py-2 rounded-xl shadow-sm"
                :class="mensaje.empleado_id == userId ? 'bg-indigo-200' : 'bg-gray-200'">
                {{ mensaje.mensaje }}
              </span>
            </div>

            <!-- Nuevo mensaje -->
            <div class="flex mt-3">
              <input v-model="nuevoMensaje[ticket.id]" type="text"
                class="form-input flex-1 border rounded-lg px-3 py-2 mr-2" placeholder="Escribe un mensaje..." />

              <button @click="enviarMensaje(ticket)"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-500">
                Enviar
              </button>
            </div>
          </div>
        </div>
      </div>

      <p v-else class="text-gray-500 italic">No hay tickets registrados.</p>
    </div>

    <!-- ===================== MODAL TICKET ===================== -->
    <div v-if="modalTicket.abierto" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-xl shadow-lg w-96 border">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Crear Ticket</h2>

        <label class="font-semibold text-gray-700 mb-1 block">Detalles</label>
        <textarea v-model="nuevoTicket.detalles" rows="4" class="form-input w-full border rounded-lg p-2"></textarea>

        <div class="flex justify-end mt-5 gap-3">
          <button @click="cerrarModalTicket"
            class="px-4 py-2 bg-gray-400 text-white rounded-lg shadow hover:bg-gray-500">
            Cancelar
          </button>

          <button @click="guardarTicket"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-500">
            Enviar
          </button>
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
    userId: Number,
  },
  layout: Layout,
  data() {
    return {
      modalTicket: { abierto: false },
      nuevoTicket: { detalles: "" },
      nuevoMensaje: {},
      pago: {
        tipo_pago: "",
      },
    };
  },
  computed: {
    totalCalculado() {
      return this.reserva.detalles.reduce((sum, d) => sum + Number(d.precio), 0);
    }
  },
  methods: {
    abrirModalTicket() {
      this.modalTicket.abierto = true;
    },
    cerrarModalTicket() {
      this.modalTicket.abierto = false;
      this.nuevoTicket.detalles = "";
    },
    guardarTicket() {
      const data = new FormData();
      data.append("detalles", this.nuevoTicket.detalles);
      data.append("reserva_id", this.reserva.id);

      this.$inertia.post("/inf513/grupo10sc/proyecto2/sis-maestranza/public/tickets", data, {
        onSuccess: () => {
          this.modalTicket.abierto = false;
          this.nuevoTicket.detalles = "";
        },
      });
    },
    abrirChat(ticket) {
      ticket.chatAbierto = !ticket.chatAbierto;
    },
    enviarMensaje(ticket) {
      if (!this.nuevoMensaje[ticket.id]) return;

      const data = new FormData();
      data.append("mensaje", this.nuevoMensaje[ticket.id]);
      data.append("ticket_id", ticket.id);
      data.append("empleado_id", this.userId);

      this.$inertia.post("/inf513/grupo10sc/proyecto2/sis-maestranza/public/mensajes", data, {
        onSuccess: () => {
          this.nuevoMensaje[ticket.id] = "";
        },
      });
    },

    /* ===================== PLAN DE PAGO ===================== */
    guardarPlanPago() {
      if (!this.pago.tipo_pago) {
        alert("Debe seleccionar un tipo de pago.");
        return;
      }

      let total = this.totalCalculado;

      const data = new FormData();
      data.append("reserva_id", this.reserva.id);
      data.append("tipo_pago", this.pago.tipo_pago);
      data.append("total", total);
      data.append("saldo", total);

      this.$inertia.post(
        "/inf513/grupo10sc/proyecto2/sis-maestranza/public/planes-pago",
        data,
        {
          onSuccess: () => {
            this.pago.tipo_pago = "";
          },
        }
      );
    }

  },
};
</script>
