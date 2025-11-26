<template>
  <div :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }" class="min-h-screen p-6">

    <Head title="Detalle del Plan de Pago" />

    <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--color-text)' }">
      Plan de Pago - Reserva #{{ planPago.reserva.id }}
    </h1>

    <!-- Datos de la Reserva -->
    <div class="rounded-xl border shadow-md p-6 mb-6" :style="{ backgroundColor: 'var(--color-card-bg)' }">
      <p><strong>Cliente:</strong> {{ planPago.reserva.cliente.nombres }} {{ planPago.reserva.cliente.apellidos }}</p>
      <p><strong>Fecha:</strong> {{ planPago.reserva.fecha }}</p>
      <p><strong>Hora:</strong> {{ planPago.reserva.hora }}</p>
      <p><strong>Tipo de Pago:</strong> {{ planPago.tipo_pago }}</p>
      <p><strong>Total:</strong> Bs {{ planPago.total }}</p>
      <p><strong>Saldo:</strong> Bs {{ planPago.saldo }}</p>
    </div>

    <!-- Detalles y Avances -->
    <div class="mb-6">
      <h2 class="text-2xl font-bold mb-4" :style="{ color: 'var(--color-text)' }">Servicios y Avances</h2>

      <div v-for="detalle in planPago.reserva.detalles" :key="detalle.id"
           class="rounded-lg border shadow p-4 mb-4"
           :style="{ backgroundColor: 'var(--color-card-bg)' }">
        <p><strong>Servicio:</strong> {{ detalle.servicio?.nombre ?? 'Sin servicio' }}</p>
        <p><strong>Detalle:</strong> {{ detalle.detalles ?? 'Sin detalle' }}</p>
        <p><strong>Precio:</strong> Bs {{ detalle.precio ?? 0 }}</p>
        <p><strong>Trabajador:</strong> {{ detalle.trabajador?.nombres ?? '' }} {{ detalle.trabajador?.apellidos ?? '' }}</p>

        <div v-if="detalle.avances && detalle.avances.length" class="mt-3">
          <h3 class="font-semibold mb-2" :style="{ color: 'var(--color-text)' }">Avances</h3>
          <ul class="space-y-2">
            <li v-for="avance in detalle.avances" :key="avance.id" class="p-2 border rounded"
                :style="{ backgroundColor: 'var(--color-card-bg)' }">
              <p><strong>Notas:</strong> {{ avance.notas }}</p>
              <p><strong>Estado:</strong> {{ avance.estado_avance }}</p>
              <p v-if="avance.foto">
                <strong>Foto:</strong>
                <img :src="`/storage/${avance.foto}`" class="h-24 mt-1 rounded" />
              </p>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Historial de Pagos -->
    <div v-if="planPago.detalle_pagos && planPago.detalle_pagos.length" class="mb-6">
      <h2 class="text-2xl font-bold mb-4" :style="{ color: 'var(--color-text)' }">Historial de Pagos</h2>
      <ul>
        <li v-for="pago in planPago.detalle_pagos" :key="pago.id"
            class="p-3 border rounded mb-2 flex justify-between"
            :style="{ backgroundColor: 'var(--color-card-bg)' }">
          <div>
            <p><strong>Fecha:</strong> {{ pago.fecha }}</p>
            <p><strong>Monto:</strong> Bs {{ pago.monto }}</p>
            <p><strong>Saldo restante:</strong> Bs {{ pago.saldo }}</p>
            <p><strong>Método:</strong> {{ pago.metodo_pago?.nombre ?? '-' }}</p>
          </div>
          <div v-if="pago.comprobante">
            <a :href="`/storage/${pago.comprobante}`" target="_blank"
               class="text-blue-600 underline">Ver comprobante</a>
          </div>
        </li>
      </ul>
    </div>

    <p v-else class="text-gray-500 italic">No se han registrado pagos aún.</p>
  </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";

export default {
  layout: Layout,
  props: {
    planPago: Object
  }
};
</script>
