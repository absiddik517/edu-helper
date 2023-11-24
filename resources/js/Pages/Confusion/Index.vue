<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray">
          <template #title>
            {{ confusion.data.name }} ({{ confusion.data.catagory }})
            <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <Link
              :href="route('confuse.create')"
              class="mr-2"
              type="button"
              varient="light"
            >
              Create
            </Link>
          </template>
          <div>
            <pre>{{confusion}}</pre>
            <table class="table table-border table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Catagory</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                
              </tbody>
            </table>
          </div>
        </Card>
      </div>
    </div>
  </Content>

  
  
</template>

<script>
import {
  AdminLayout,
  Modal,
  Input,
  Select,
  Card,
  DeleteConfirm,
  Spinner,
  Dropdown,
  Pagination,
  Filterth,
  Button,
  Content,
  useValidateForm,
} from "@/Components";
import toast from "@/Store/toast.js";
import _ from "lodash";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";
import {months} from "@/Store/static.js";

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
    Select,
    Button,
    Dropdown,
    Filterth,
    Pagination,
  },
  props: {
    confusion: Object,
  },
  data() {
    return {
      
    };
  },
  methods: {
    
  },
};
</script>

<style>
  .event{
    border: 1px solid #bbb;
    border-radius: 4px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0px 0px 5px #666;
  }
  .event:last-child{
    margin-bottom: 0;
  }
  
  .title {
    font-weight: 600;
    font-size: 18px;
    color: Dark;
  }
  
  .detail {
    text-align: justify;
    color: #4e4e4ef9;
  }
  
</style>
