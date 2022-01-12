<template>
  <Link :href="'/'" class="d-flex justify-content-center mb-4">
    <img
      :src="'/images/logo.png'"
      alt="Logo"
      class="img-circle elevation-3"
      style="opacity: 0.8"
    />
  </Link>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
  components: {
    Link,
  },
});
</script>