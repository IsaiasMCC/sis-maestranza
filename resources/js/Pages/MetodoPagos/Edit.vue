<template>
  <div :style="{ color: 'var(--color-text)' }">
    <Head :title="form.nombre" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">
      <Link class="hover:underline" :style="{ color: 'var(--color-primary)' }" href="/metodo-pagos">Métodos de Pago</Link>
      <span class="font-medium mx-1">/</span> {{ form.nombre }}
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden transition-colors" :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.nombre" :error="form.errors.nombre" class="pb-8 pr-6 w-full" label="Nombre"/>
          <select-input v-model="form.estado" :error="form.errors.estado" class="pb-8 pr-6 w-full" label="Estado">
            <option :value="true">Activo</option>
            <option :value="false">Inactivo</option>
          </select-input>
        </div>

        <div class="flex items-center px-8 py-4 border-t transition-colors" :style="{ backgroundColor: 'var(--color-bg)', borderColor: 'var(--color-text)' }">
          <button v-if="!form.processing" class="text-red-600 hover:underline" type="button" @click="destroy">Eliminar</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Actualizar Método de Pago</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: { Head, Link, Layout, TextInput, SelectInput, LoadingButton },
  layout: Layout,
  props: { metodo: Object },
  data() {
    return {
      form: this.$inertia.form({
        nombre: this.metodo.nombre,
        estado: this.metodo.estado,
      })
    }
  },
  methods: {
    update() { this.form.put(`/metodo-pagos/${this.metodo.id}`) },
    destroy() {
      if(confirm('¿Estás seguro de eliminar este método de pago?')){
        this.$inertia.delete(`/metodo-pagos/${this.metodo.id}`)
      }
    }
  }
}
</script>
