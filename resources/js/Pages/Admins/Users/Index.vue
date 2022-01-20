<template>
  <div>
    <admin-layout>
      <template #header>
        <h1 class="m-0">Permissions</h1>
      </template>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <div class="d-flex justify-content-end">
                    <div class="col-xs-4 mr-2">
                      <input
                        v-model="searchTerm"
                        type="search"
                        placeholder="Search..."
                        class="form-control"
                      />
                    </div>

                    <div
                      class="card-tools"
                      v-if="
                        $page.props.auth.hasRole.superAdmin ||
                        $page.props.auth.hasRole.admin
                      "
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
                          <th class="text-capitalize">Permissions Name</th>
                          <th class="text-capitalize">Description</th>
                          <th class="text-capitalize">Created</th>
                          <th
                            class="text-capitalize text-right"
                            v-if="
                              $page.props.auth.hasRole.superAdmin ||
                              $page.props.auth.hasRole.admin
                            "
                          >
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(permission, index) in permissions.data"
                          :key="index"
                        >
                          <td class="text-capitalize">{{ permission.name }}</td>
                          <td>{{ permission.description }}</td>
                          <td>{{ permission.created_at }}</td>
                          <td
                            class="text-right"
                            v-if="
                              $page.props.auth.hasRole.superAdmin ||
                              $page.props.auth.hasRole.admin
                            "
                          >
                            <button
                              class="btn btn-success text-uppercase"
                              style="letter-spacing: 0.1em"
                              @click="editModal(permission)"
                            >
                              <i class="far fa-edit"></i>
                            </button>
                            <button
                              class="btn btn-danger text-uppercase ml-1"
                              style="letter-spacing: 0.1em"
                              @click="deletePermission(permission)"
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
                  <pagination :links="permissions.links"></pagination>
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
              <div class="h4">
                <span
                  >Preview:
                  <span class="text-capitalize">{{ form.name }}</span></span
                >
              </div>
              <div class="card card-primary">
                <form @submit.prevent="checkMode">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="permission" class="h4">Permission Name</label>
                      <input
                        type="text"
                        class="form-control"
                        id="permission"
                        placeholder="Permission Name"
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

                    <div class="form-group">
                      <label for="permission" class="h4">Description</label>
                      <textarea
                        type="text"
                        class="form-control"
                        id="permission"
                        placeholder="Description"
                        v-model="form.description"
                        :class="{
                          'is-invalid': form.errors.description,
                        }"
                        autofocus="autofocus"
                        autocomplete="off"
                      ></textarea>
                    </div>
                    <div
                      class="invalid-feedback mb-3"
                      :class="{ 'd-block': form.errors.description }"
                    >
                      {{ form.errors.description }}
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
                        !form.name || !form.description || form.processing
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
  props: ["permissions"],
  components: {
    AdminLayout,
    Pagination,
  },
  data() {
    return {
      searchTerm: "",
      editedIndex: -1,
      editMode: false,
      form: this.$inertia.form({
        id: "",
        name: "",
        description: "",
      }),
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1
        ? "Create New Permission"
        : "Edit Permission";
    },

    btnText() {
      return this.editedIndex === -1 ? "Create" : "Edit";
    },
    checkMode() {
      return this.editMode === false
        ? this.createPermission()
        : this.editPermission();
    },
  },
  methods: {
    openModal() {
      this.editMode = false;
      this.form.reset();
      this.editedIndex = -1;
      $("#modal-lg").modal("show");
    },
    closeModal() {
      this.editMode = false;
      this.form.reset();
      $("#modal-lg").modal("hide");
    },
    editModal(permission) {
      this.editMode = true;
      $("#modal-lg").modal("show");
      this.editedIndex = this.permissions.data.indexOf(permission);
      this.form.name = permission.name;
      this.form.id = permission.id;
      this.form.description = permission.description;
    },
    createPermission() {
      this.form.post(this.route("admin.permissions.store"), {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.closeModal();
          Toast.fire({
            icon: "success",
            title: "New permission created!",
          });
        },
      });
    },
    editPermission() {
      this.form.patch(
        this.route("admin.permissions.update", this.form.id, this.form),
        {
          preserveScroll: true,
          onSuccess: () => {
            this.form.reset();
            this.closeModal();
            Toast.fire({
              icon: "success",
              title: "Permission has been updated successfully!",
            });
          },
        }
      );
    },
    deletePermission(permission) {
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
          this.form.delete(
            this.route("admin.permissions.destroy", permission),
            {
              preserveScroll: true,
              onSuccess: () => {
                Swal.fire(
                  "Deleted!",
                  "Permission has been deleted successfully.",
                  "success"
                );
              },
            }
          );
        }
      });
    },
  },
};
</script>