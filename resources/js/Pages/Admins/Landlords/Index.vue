<template>
  <div>
    <admin-layout>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Landlords</h3>

                  <div class="d-flex justify-content-end">
                    <div class="input-group mr-2 col-6">
                      <input
                        type="search"
                        class="form-control"
                        placeholder="Search for..."
                        aria-describedby="basic-addon2"
                      />
                      <div class="input-group-append">
                        <button class="btn btn-info" type="button">
                          <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                      </div>
                    </div>
                    <div
                      class="card-tools"
                      v-if="$page.props.auth.hasRole.admin"
                    >
                      <button
                        type="button"
                        class="btn btn-info text-uppercase"
                        style="letter-spacing: 0.1em"
                        @click="openModal"
                      >
                        <i class="fas fa-plus-circle"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th class="text-capitalize">
                            Name
                            <span>&uarr;</span><span>&darr;</span>
                          </th>
                          <th class="text-capitalize">
                            Email <span>&uarr;</span><span>&darr;</span>
                          </th>
                          <th class="text-capitalize">Phone number</th>
                          <th class="text-capitalize">CID number</th>
                          <th
                            class="text-capitalize text-right"
                            v-if="$page.props.auth.hasRole.admin"
                          >
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(user, index) in landlords.data"
                          :key="index"
                        >
                          <td class="text-capitalize">{{ user.name }}</td>
                          <td>{{ user.email }}</td>
                          <td>{{ user.contact }}</td>
                          <td>{{ user.cid_number }}</td>
                          <td
                            class="text-right"
                            v-if="$page.props.auth.hasRole.admin"
                          >
                            <button
                              class="btn btn-success text-uppercase"
                              style="letter-spacing: 0.1em"
                              @click="editModal(user)"
                            >
                              <i class="far fa-edit"></i>
                            </button>
                            <button
                              class="btn btn-danger text-uppercase ml-1"
                              style="letter-spacing: 0.1em"
                              @click="deleteLandlord(user)"
                            >
                              <i class="fas fa-trash"></i>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer clearfix">
                  <pagination :links="landlords.links"></pagination>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title">{{ formTitle }}</h6>
              <button
                type="button"
                class="close"
                @click="closeModal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body overflow-hidden">
              <div class="card card-primary">
                <form @submit.prevent="checkMode">
                  <div class="card-body">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="user" class="h6">Name</label>
                        <input
                          type="text"
                          class="form-control"
                          id="user"
                          placeholder="Name"
                          v-model="form.name"
                          :class="{
                            'is-invalid': form.errors.name,
                          }"
                          autofocus="autofocus"
                          autocomplete="off"
                        />
                      </div>
                      <div
                        class="invalid-feedback mb-3"
                        :class="{ 'd-block': form.errors.name }"
                      >
                        {{ form.errors.name }}
                      </div>

                      <div class="form-group col-md-6">
                        <label for="user" class="h6">Email</label>
                        <input
                          type="email"
                          class="form-control"
                          id="user"
                          placeholder="example@gmail.com"
                          v-model="form.email"
                          :class="{
                            'is-invalid': form.errors.name,
                          }"
                          autofocus="autofocus"
                          autocomplete="off"
                        />
                      </div>
                      <div
                        class="invalid-feedback mb-3"
                        :class="{ 'd-block': form.errors.name }"
                      >
                        {{ form.errors.name }}
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="user" class="h6">Phone number</label>
                      <input
                        type="phone"
                        class="form-control"
                        id="user"
                        placeholder="Contact number"
                        v-model="form.contact"
                        :class="{
                          'is-invalid': form.errors.name,
                        }"
                        autofocus="autofocus"
                        autocomplete="off"
                      />
                    </div>
                    <div
                      class="invalid-feedback mb-3"
                      :class="{ 'd-block': form.errors.name }"
                    >
                      {{ form.errors.name }}
                    </div>

                    <div class="form-group">
                      <label for="user" class="h6">CID number</label>
                      <input
                        type="text"
                        class="form-control"
                        id="user"
                        placeholder="CID number"
                        v-model="form.cid_number"
                        :class="{
                          'is-invalid': form.errors.name,
                        }"
                        autofocus="autofocus"
                        autocomplete="off"
                      />
                    </div>
                    <div
                      class="invalid-feedback mb-3"
                      :class="{ 'd-block': form.errors.name }"
                    >
                      {{ form.errors.name }}
                    </div>
                  </div>

                  <div class="modal-footer justify-content-between">
                    <button
                      type="button"
                      class="btn btn-danger text-uppercase"
                      style="letter-spacing: 0.1em"
                      @click="closeModal"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      class="btn btn-info text-uppercase"
                      style="letter-spacing: 0.1em"
                      :disabled="
                        !form.name ||
                        !form.email ||
                        !form.contact ||
                        !form.cid_number ||
                        form.processing
                      "
                    >
                      <div
                        v-show="form.processing"
                        class="spinner-border spinner-border-sm"
                        role="status"
                      >
                        <span class="visually-hidden"></span>
                      </div>
                      {{ btnText }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </admin-layout>
  </div>
</template>
<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import Pagination from "@/Components/Pagination";

export default {
  props: ["landlords"],
  components: { AdminLayout, Pagination },
  data() {
    return {
      editedIndex: -1,
      editMode: false,
      form: this.$inertia.form({
        id: "",
        name: "",
        email: "",
        contact: "",
        cid_number: "",
      }),
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Create New Landlord" : "Edit Landlord";
    },

    btnText() {
      return this.editedIndex === -1 ? "Create" : "Edit";
    },
    checkMode() {
      return this.editMode === false
        ? this.createLandlord()
        : this.editLandlord();
    },
  },
  methods: {
    
    openModal() {
      this.form.clearErrors();
      this.editMode = false;
      this.form.reset();
      this.editedIndex = -1;
      $("#modal-lg").modal("show");
    },
    closeModal() {
      this.form.clearErrors();
      this.editMode = false;
      this.form.reset();
      $("#modal-lg").modal("hide");
    },
    editModal(user) {
      this.editMode = true;
      $("#modal-lg").modal("show");
      this.editedIndex = this.landlords.data.indexOf(user);
      this.form.name = user.name;
      this.form.email = user.email;
      this.form.id = user.id;
      this.form.contact = user.contact;
      this.form.cid_number = user.cid_number;
    },
    createLandlord() {
      this.form.post(this.route("admin.landlords.store"), {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.closeModal();
          Toast.fire({
            icon: "success",
            title: "New user created!",
          });
        },
      });
    },
    editLandlord() {
      this.form.patch(
        this.route("admin.landlords.update", this.form.id, this.form),
        {
          preserveScroll: true,
          onSuccess: () => {
            this.form.reset();
            this.closeModal();
            Toast.fire({
              icon: "success",
              title: "Landlord has been updated successfully!",
            });
          },
        }
      );
    },
    deleteLandlord(user) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          this.form.delete(this.route("admin.landlords.destroy", user), {
            preserveScroll: true,
            onSuccess: () => {
              Swal.fire(
                "Deleted!",
                "Landlord has been deleted successfully.",
                "success"
              );
            },
          });
        }
      });
    },
  },
};
</script>