<template>
  <Head title="Welcome" />

  <div class="mx-3">
    <nav class="navbar navbar-light bg-light justify-content-between">
      <Link :href="route('dashboard')" class="text-muted font-weight-bold">
        <img
          :src="'/images/favicon-32x32.png'"
          alt="Logo"
          class="brand-image img-circle elevation-3"
          style="opacity: 1"
        />
        Town Home
      </Link>

      <div v-if="canLogin" class="d-flex justify-content-end">
        <div>
          <form v-if="$page.props.user" @submit.prevent="logout">
            <button class="btn">
              <i class="fas fa-power-off" style="color: rgb(44, 66, 105)"></i>
            </button>
          </form>
          <template v-if="!$page.props.user">
            <Link :href="route('login')" class="text-muted"> Log in </Link>

            <Link
              v-if="canRegister"
              :href="route('register')"
              class="ms-4 text-muted"
            >
              Register
            </Link>
          </template>
        </div>
      </div>
    </nav>

    <div class="bg-image">
      <div class="justify-content-between">
        <h2 class="css-19f84j9">Find your fresh start</h2>
        <h1 class="css-1v4pdp8">Houses and apartments for rent.</h1>
        <form action="" class="container">
          <input
            class="form-control"
            type="search"
            placeholder="Where do you want to live?"
          />
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>

<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
  components: {
    Head,
    Link,
  },

  props: {
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
  },

  methods: {
    logout() {
      Swal.fire({
        title: "Are you sure you want to log out?",
        text: "You are about to exit the system",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          this.$inertia.post(route("logout"), {
            preserveScroll: true,
          });
        }
      });
    },
  },
});
</script>
