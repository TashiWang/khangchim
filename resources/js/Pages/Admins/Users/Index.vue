<template>
  <div>
    <admin-layout>
      <template #header>
        <h1 class="m-0">Users</h1>
      </template>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <div class="d-flex justify-content-end"></div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th class="text-capitalize">Name</th>
                          <th class="text-capitalize">Email</th>
                          <th class="text-capitalize">Contact number</th>
                          <th class="text-capitalize">CID number</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(user, id) in users.data" :key="id">
                          <td class="text-capitalize">{{ user.name }}</td>
                          <td>{{ user.email }}</td>
                          <td>{{ user.contact }}</td>
                          <td>{{ user.cid_number }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer clearfix">
                  <pagination :links="users.links"></pagination>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </admin-layout>
  </div>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import Pagination from "@/Components/Pagination";

export default {
  props: ["users"],
  components: {
    AdminLayout,
    Pagination,
  },
};
</script>