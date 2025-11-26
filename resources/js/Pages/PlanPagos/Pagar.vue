<template>
  <div>
    <Head title="Pago del Plan" />

    <h1 class="text-3xl font-bold mb-6">Pago de Reserva #{{ planPago.reserva_id }}</h1>

    <!-- Estado de pago -->
    <div v-if="planPago.saldo == 0" class="p-4 bg-green-100 text-green-800 rounded-lg mb-6">
      ✅ Este plan ya está totalmente pagado.
    </div>

    <!-- Formulario de pago solo si saldo > 0 -->
    <div v-else class="p-6 bg-white border shadow rounded-lg mb-6">
      <h2 class="text-2xl font-semibold mb-4">Registrar Pago</h2>

      <!-- Monto -->
      <div class="mb-4">
        <label class="block font-semibold text-gray-700 mb-1">Monto a pagar (saldo disponible: Bs {{ planPago.saldo }})</label>
        <input
          type="number"
          v-model.number="form.monto"
          class="form-input w-full border rounded-lg p-2"
          :readonly="planPago.tipo_pago === 'CONTADO'"
          :max="planPago.saldo"
        />
      </div>

      <!-- Método de Pago -->
      <div class="mb-4">
        <label class="block font-semibold text-gray-700 mb-1">Método de Pago</label>
        <select v-model="form.metodo_pago_id" class="form-input w-full border rounded-lg p-2">
          <option value="" disabled>Seleccione método...</option>
          <option v-for="m in metodos" :key="m.id" :value="m.id">{{ m.nombre }}</option>
        </select>
      </div>

      <!-- Comprobante (opcional) -->
      <div class="mb-4">
        <label class="block font-semibold text-gray-700 mb-1">Comprobante (opcional)</label>
        <input type="file" @change="handleFile" class="form-input w-full border rounded-lg p-2"/>
      </div>

      <button @click="guardarPago"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-500">
        Registrar Pago
      </button>
    </div>

    <!-- Historial de pagos -->
    <div v-if="planPago.detalle_pagos.length" class="bg-gray-50 p-6 rounded-lg border shadow">
      <h2 class="text-2xl font-semibold mb-4">Historial de Pagos</h2>

      <table class="w-full table-auto">
        <thead>
          <tr class="bg-gray-200">
            <th class="px-4 py-2 text-left">Fecha</th>
            <th class="px-4 py-2 text-left">Monto</th>
            <th class="px-4 py-2 text-left">Saldo</th>
            <th class="px-4 py-2 text-left">Método</th>
            <th class="px-4 py-2 text-left">Comprobante</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="d in planPago.detalle_pagos" :key="d.id" class="border-b">
            <td class="px-4 py-2">{{ d.fecha }}</td>
            <td class="px-4 py-2">Bs {{ d.monto }}</td>
            <td class="px-4 py-2">Bs {{ d.saldo }}</td>
            <td class="px-4 py-2">{{ d.metodo_pago?.nombre }}</td>
            <td class="px-4 py-2">
              <a v-if="d.comprobante" :href="`/${d.comprobante}`" target="_blank" class="text-blue-600 underline">Ver</a>
              <span v-else>-</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';

export default {
  layout: Layout,
  props: {
    planPago: Object,
    metodos: Array,
  },
  data() {
    return {
      form: {
        monto: this.planPago.tipo_pago === 'CONTADO' ? this.planPago.saldo : 0,
        metodo_pago_id: '',
        comprobante: null,
      },
    }
  },
  methods: {
    handleFile(event) {
      this.form.comprobante = event.target.files[0];
    },
    guardarPago() {
      if (!this.form.monto || !this.form.metodo_pago_id) {
        alert("Debe completar todos los campos.");
        return;
      }

      const data = new FormData();
      data.append('monto', this.form.monto);
      data.append('metodo_pago_id', this.form.metodo_pago_id);
      if (this.form.comprobante) {
        data.append('comprobante', this.form.comprobante);
      }

      // Fecha automática
      data.append('fecha', new Date().toISOString().substr(0,10));

      this.$inertia.post(`/inf513/grupo10sc/proyecto2/sis-maestranza/public/plan-pagos/${this.planPago.id}/pagar`, data, { preserveScroll: true });
    }
  }
}
</script>
