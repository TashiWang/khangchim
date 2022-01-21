<template>
  <div>
    <admin-layout>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Areas</h3>

                  <div class="card-tools">
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
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th class="text-capitalize">Name</th>
                          <th class="text-capitalize">Created by</th>
                          <th class="text-capitalize"> Creator email</th>
                          <th class="text-capitalize text-right">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(area, id) in areas.data" :key="id">
                          <td class="text-capitalize">{{ area.name }}</td>
                          <td class="text-capitalize">{{ area.owner }}</td>
                          <td>{{ area.owner_email }}</td>
                          <td class="text-right">
                            <button
                              class="btn btn-success text-uppercase"
                              style="letter-spacing: 0.1em"
                              @click="editModal(area)"
                            >
                              <i class="far fa-edit"></i>
                            </button>
                            <button
                              class="btn btn-danger text-uppercase ml-1"
                              style="letter-spacing: 0.1em"
                              @click="deleteArea(area)"
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
                  <pagination :links="areas.links"></pagination>
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
              <h4 class="modal-title">{{ formTitle }}</h4>
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
                    <div class="form-group">
                      <label for="area" class="h4">Area Name</label>
                      <input
                        type="text"
                        class="form-control"
                        id="area"
                        placeholder="area"
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
                      :disabled="!form.name || form.processing"
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
  props: ["areas"],
  components: {
    AdminLayout,
    Pagination,
  },
  data() {
    return {
      editedIndex: -1,
      editMode: false,
      form: this.$inertia.form({
        id: "",
        name: "",
        user_id: "",
      }),
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Create New Role" : "Edit Role";
    },

    btnText() {
      return this.editedIndex === -1 ? "Create" : "Edit";
    },
    checkMode() {
      return this.editMode === false ? this.createArea() : this.editRole();
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
    editModal(area) {
      this.editMode = true;
      $("#modal-lg").modal("show");
      this.editedIndex = this.areas.data.indexOf(area);
      this.form.id = area.id;
      this.form.name = area.name;
      this.form.user_id = area.user_id;
    },
    createArea() {
      this.form.post(this.route("admin.areas.store"), {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.closeModal();
          Toast.fire({
            icon: "success",
            title: "New area created!",
          });
        },
      });
    },
    editRole() {
      this.form.patch(
        this.route("admin.areas.update", this.form.id, this.form),
        {
          preserveScroll: true,
          onSuccess: () => {
            this.form.reset();
            this.closeModal();
            Toast.fire({
              icon: "success",
              title: "Role has been updated successfully!",
            });
          },
        }
      );
    },
    deleteArea(area) {
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
          this.form.delete(this.route("admin.areas.destroy", area), {
            preserveScroll: true,
            onSuccess: () => {
              Swal.fire(
                "Deleted!",
                "Role has been deleted successfully.",
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