<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Books
            <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <Link
              :href="route('books.create')"
              class="mr-2"
              type="button"
              varient="light"
            >
              Create
            </Link>
          </template>
          <div class="row p-2 gy-2">
            <div class="col-6">
              <input
                v-model="filter.search"
                type="text"
                placeholder="Search..."
                class="form-control"
              />
              <i
                v-show="filter.search"
                @click="filter.search = null"
                class="fa fa-times filter-close"
                style="right: 15px"
              ></i>
            </div>
            <div class="col-6 d-flex justify-content-end align-items-center">
              Show
              <select
                v-model="filter.per_page"
                class="ml-2 select_per_page"
                id="per_page"
              >
                <option disabled value="null">🔻</option>
                <option v-for="index in 100" :value="index * 5">
                  {{ index * 5 }}
                </option>
                <option value="all">All</option>
              </select>
            </div>
          </div>
          <div class="row p-2 gy-2">
            <div class="col-6">
              <input
                v-model="filter.name"
                type="text"
                placeholder="Book Name"
                class="form-control"
              />
              <i
                v-show="filter.name"
                @click="filter.name = null"
                class="fa fa-times filter-close"
                style="right: 15px"
              ></i>
            </div>
            <div class="col-6">
              <input
                v-model="filter.author"
                type="text"
                placeholder="Author"
                class="form-control"
              />
              <i
                v-show="filter.author"
                @click="filter.author = null"
                class="fa fa-times filter-close"
                style="right: 15px"
              ></i>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover y-middle">
            <div v-show="loadingTable" class="overlays">
              <span>Loading... <i class="fa fa-spin fa-spinner"></i></span>
            </div>
            <thead>
              <tr>
                <th>#</th>
                <th>Type</th>
                <th>Name</th>
                <th>Author</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(book, index) in books.data">
                <td>{{ index + 1 }}</td>
                <td>{{ book.catagory_name }}</td>
                <td>{{ book.name }}</td>
                <td>{{ book.author }}</td>
                <td class="text-right">
                  <Link class="mr-2 btn btn-info btn-sm" :href="route('books.edit', book.id)"><i class="fa fa-edit"></i></Link>
                  <Button class="btn-sm" @click="deleteBook(book.id)" varient="danger"><i class="fa fa-trash"></i></Button>
                </td>
              </tr>
            </tbody>
          </table>
          </div>
        </Card>
      </div>
    </div>
  </Content>
  
  <DeleteConfirm :deleteUrl="deleteUrl" item="book" />
</template>

<script>
import {
  AdminLayout,
  Modal,
  Input,
  Card,
  DeleteConfirm,
  Spinner,
  Pagination,
  Button,
  Content,
  useValidateForm,
} from "@/Components";
import toast from "@/Store/toast.js";
import _ from "lodash";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";
import { isRequired } from "intus/rules";

export default {
  name: "Permission",
  layout: AdminLayout,
  components: {
    Spinner,
    Modal,
    Content,
    DeleteConfirm,
    Card,
    Input,
    Button,
    Pagination,
  },
  props: {
    books: Object,
    params: Object,
  },
  data() {
    return {
      filter: reactive({
        search: this.params.search,
        name: this.params.name,
        author: this.params.author,
        per_page: this.params.per_page,
      }),
      modal: {
        confirm: null
      },
      loadingTable: false,
      deleteUrl: null,
    };
  },
  mounted() {
    let confirm = document.querySelector("#confirmModel");
    this.modal.confirm = new bootstrap.Modal(confirm);
  },
  methods: {
    deleteBook(id) {
      this.deleteUrl = this.route('books.delete', id);
      this.modal.confirm.show();
      Inertia.on("finish", () => {
        this.deleteUrl = null;
        this.modal.confirm.hide();
      });
    },
    getBooks(filter = {}) {
      this.loadingTable = true;
      Inertia.get(this.route("books.index"), filter, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: () => {
          this.loadingTable = false;
        },
        onError: (error) => {
          this.loadingTable = false;
          let message = "";
          for (let key in error) {
            message += error[key] + " ";
          }
          toast.add({
            type: "error",
            message,
          });
        },
      });
    },
  },
  watch: {
    filter: {
      handler: _.debounce(function (state, old) {
        let query = {};
        if (state.search) query.search = state.search;
        if (state.name) query.name = state.name;
        if (state.author) query.author = state.author;
        if (state.per_page) query.per_page = state.per_page;

        this.getBooks(query);
      }, 1000),
      deep: true,
    },
  },
};
</script>
