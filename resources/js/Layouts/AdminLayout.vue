<template>
  <div class="wrapper">
    <!-- Preloader -->

    <!-- <div
      class="preloader flex-column justify-content-center align-items-center"
    >
      <div
        class="spinner-border text-primary"
        style="width: 4rem; height: 4rem"
        role="status"
      >
        <span class="sr-only">Loading...</span>
      </div>
    </div> -->

    <!-- Navbar -->
    <NavBar />
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <SideBar />

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <slot name="header"></slot>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <slot></slot>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <Footer />
  </div>
</template>

<script>
import Footer from "@/Components/Footer";
import NavBar from "@/Components/NavBar";
import SideBar from "@/Components/SideBar";
export default {
  components: {
    Footer,
    NavBar,
    SideBar,
  },
  mounted() {
    this.init();
  },
  methods: {
    init() {
      let SELECTOR_LOADER = ".preloader";
      setTimeout(() => {
        let $loader = $(SELECTOR_LOADER);
        if ($loader) {
          $loader.css("height", 0);
          setTimeout(() => {
            $loader.children().hide();
          }, 100);
        }
      }, 1000);
    },
  },
};
</script>