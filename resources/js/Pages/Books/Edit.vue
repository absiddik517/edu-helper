<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Edit Books
          </template>
          <template #title_right>
            <Link
              :href="route('books.index')"
              class="mr-2"
              type="button"
              varient="light"
            >
              Go back
            </Link>
          </template>
          <div class="px-4 py-2">
            <form @submit.prevent="submit">
              <Select type="select" :error="form.errors.catagory_id" v-model="form.catagory_id" :options="catagories" field="catagory_id" label-text="Catagory" :form="form"/>
              <Input type="text" v-model="form.name" autocomplete="events.name" field="name" label="Name" :form="form"/>
              <Input type="text" v-model="form.author" autocomplete="events.author" field="author" label="Author" :form="form"/>
              <div class="form-group">
                <label for="detail">Addition Data</label>
                <textarea v-model="form.description" class="form-control" id="" cols="30" rows="5"></textarea>
              </div>
              <div class="form-group text-right">
                <Button :is-loading="form.processing" hide-label><i class="fa fa-save"></i></Button>
              </div>
            </form>
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
  Spinner,
  Button,
  Content
} from "@/Components";
import { Inertia } from "@inertiajs/inertia";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
  name: "Create",
  layout: AdminLayout,
  components: {
    Spinner,
    Modal,
    Content,
    Card,
    Input,
    Select,
    Button
  },
  props: {
    catagories: Object,
    book: Object,
  },
  data() {
    return {
      form: useForm({
        name: this.book.name,
        author: this.book.author,
        description: this.book.description,
        catagory_id: this.book.catagory_id,
      }),
    };
  },
  methods: {
    
    submit() {
      this.form.clearErrors();
      this.form.post('', {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
          if(res.props.toast.type == 'success') {
            this.form.reset();
          }
        },
      });
    },
  },
};
</script>
