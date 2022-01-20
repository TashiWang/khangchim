<template>
  <div>
    <admin-layout>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">User Roles</h3>

                  <div class="card-tools" v-if="$page.props.auth.hasRole.admin">
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
                          <th class="text-capitalize">Role Name</th>
                          <th class="text-capitalize">Created At</th>
                          <th class="text-capitalize">Last updated at</th>
                          <th
                            class="text-capitalize text-right"
                            v-if="$page.props.auth.hasRole.admin"
                          >
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(role, index) in roles.data" :key="index">
                          <td class="text-capitalize">{{ role.name }}</td>
                          <td>{{ role.created_at }}</td>
                          <td>{{ role.updated_at }}</td>
                          <td
                            class="text-right"
                            v-if="$page.props.auth.hasRole.admin"
                          >
                            <button
                              class="btn btn-success text-uppercase"
                              style="letter-spacing: 0.1em"
                              @click="editModal(role)"
                            >
                              <i class="far fa-edit"></i>
                            </button>
                            <button
                              class="btn btn-danger text-uppercase ml-1"
                              style="letter-spacing: 0.1em"
                              @click="deleteRole(role)"
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
                  <pagination :links="roles.links"></pagination>
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
                      <label for="role" class="h4">Role Name</label>
                      <input
                        type="text"
                        class="form-control"
                        id="role"
                        placeholder="Role Name"
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
  props: ["roles"],
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
      return this.editMode === false ? this.createRole() : this.editRole();
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
    editModal(role) {
      this.editMode = true;
      $("#modal-lg").modal("show");
      this.editedIndex = this.roles.data.indexOf(role);
      this.form.name = role.name;
      this.form.id = role.id;
    },
    createRole() {
      this.form.post(this.route("admin.roles.store"), {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.closeModal();
          Toast.fire({
            icon: "success",
            title: "New role created!",
          });
        },
      });
    },
    editRole() {
      this.form.patch(
        this.route("admin.roles.update", this.form.id, this.form),
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
    deleteRole(role) {
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
          this.form.delete(this.route("admin.roles.destroy", role), {
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