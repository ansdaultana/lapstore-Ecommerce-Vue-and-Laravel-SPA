
<script setup>
import AppLayout from '../Layouts/AppLayout.vue'
import { computed, ref, onMounted, watch } from 'vue';
import router from '../router';
import store from '../store/index.js'
import CustomPagination from '../components/CustomPagination.vue';

const openmodal = ref(false);
const slugToBeDeleted = ref('')
const goToNewProduct = () => {
  router.push({ name: 'newproduct' });

}

const info = ref({
  currentPage: 1,
  perPage: 10,
  search: ''
});

const products = computed(() => store.getters.Products);
const totalItems = ref(10);
const totalPages = computed(() => Math.ceil(totalItems.value / info.value.perPage));

onMounted(() => {
  getProducts();
})


const getProducts = async () => {

  try {

    const response = await store.dispatch('getProducts', info);
    totalItems.value = response.data.total;
  } catch (error) {
    console.log(error);
  }
}
const deleteModal = (slug) => {
  ToggleModal()
  slugToBeDeleted.value = slug
}
const deleteProduct = async () => {
  try {

    const response = await store.dispatch('deleteProduct', slugToBeDeleted.value);
    getProducts()
    ToggleModal()
  } catch (error) {
    console.log(error);

  }
}

const updatecurrentPage = (page) => {
  info.value.currentPage = page;
  getProducts()
  console.log(info.value.currentPage)
}

const EditProduct = (slug) => {
  router.push({ path: `/app/edit-product/${slug}` })
}
const formatUpdatedAt = (date) => {
  const updatedDate = new Date(date);
  const currentDate = new Date();
  const diffInMilliseconds = Math.abs(currentDate - updatedDate);

  const seconds = Math.floor(diffInMilliseconds / 1000);
  const minutes = Math.floor(seconds / 60);
  const hours = Math.floor(minutes / 60);
  const days = Math.floor(hours / 24);

  if (days > 0) {
    return `${days} day${days > 1 ? 's' : ''} ago`;
  } else if (hours > 0) {
    return `${hours} hour${hours > 1 ? 's' : ''} ago`;
  } else if (minutes > 0) {
    return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
  } else {
    return `${seconds} second${seconds !== 1 ? 's' : ''} ago`;
  }
}

const ToggleModal = () => { openmodal.value = !openmodal.value }

</script>
<template>
  <AppLayout>

    <div class="relative flex-col ">

      <div class=" flex justify-between ">
        <div class="flex">
          <div class="text-black text-lg mt-2  ml-5 hover:text-black ">
            Products
          </div>

        </div>

        <div @click="goToNewProduct"
          class="
        cursor-pointer
          rotate-svg
          transition-transform hover:-translate-x-5
          md:w-36 w-14 bg-orange-500 hover:bg-orange-600  hover:scale-103 text-white px-2 rounded-lg py-1 m-2 ml-2 flex ">
          <div>
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
              class=" now-rotate-svg   transition-transform w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>

          </div>

          <button class="hidden md:block">
            New Product
          </button>
        </div>
      </div>

      <div name="panel" class=" mr-1 absolute  rounded-md border-slate-300 w-full h-full   border-t ">
        <div class=" p-2 bg-slate-50 ">
          <div class="flex justify-between  pb-3  ">
            <div class="flex">
              <div class="flex items-center ">
                <span class="whitespace-nowrap mr-3 text-black hidden md:block">Per Page</span>
                <select @change="getProducts" v-model="info.perPage"
                  class=" hidden md:block appearance-none relative  w-24 px-3 py-2 border border-slate-300 placeholder-slate-500 text-slate-900 rounded-lg focus:outline-none focus:ring-slate-500 focus:border-slate-500 focus:z-10 sm:text-sm">
                  <option value="3">3</option>
                  <option value="5">5</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <!-- <span class="ml-3 hidden md:block">Found {{ products.total }} products</span> -->
              </div>
              <div class="ml-2 -mt-2 items-center ">
                <CustomPagination v-model:currentPage="info.currentPage" :totalPages="totalPages"
                  @update:currentPage="updatecurrentPage" />

              </div>


            </div>

            <div>
              <input v-model="info.search" @input="getProducts"
                class="appearance-none relative block w-32 lg:w-48 px-3 py-2 border border-slate-300 placeholder-slate-500 text-gray-900 rounded-lg focus:outline-none focus:ring-slate-500 focus:border-slate-500 focus:z-10 sm:text-sm"
                placeholder="Type to Search products">
            </div>
          </div>



          <div class="flex bg-slate-400 p-2 border-2  rounded-lg justify-between text-white">
            <div class=" ml-5 w-20 hidden md:block">
              ID
            </div>
            <div class="w-24">
              Image
            </div>
            <div class="w-16 md:w-44">
              Title
            </div>
            <div class="w-36 hidden md:block">
              Price
            </div>
            <div class="w-40 hidden lg:block">
              Last updated At
            </div>
            <div class="w-20">
              Actions
            </div>
          </div>
        </div>

        <ul role="list"
          class="divide-y divide-slate-300 ml-5 mr-5 overflow-y-scroll overflow-x-hidden h-[500px] scrollbar text-slate-600 font-serif">
          <li v-for="product in products" :key="product.ID"
            class="cursor-pointer hover:shadow-md rounded-lg hover:bg-slate-100 flex justify-between gap-x-6 py-5 transition-transform hover:scale-103 ease-in duration-200 ">
            <div class=" ml-8 w-20   hidden md:block">
              {{ product.id }}
            </div>
            <div class="w-24 ">
              <div class=" w-16  " v-if="product.images.length > 0">
                <img :src="product.images[0].image_url" alt="" class="rounded-lg">

              </div>
            </div>
            <div class="w-16 md:w-44">
              {{ product.title }}

            </div>
            <div class="w-36 hidden md:block pl-5">
              {{ product.price }}

            </div>
            <div class="w-40 md:pl-8 hidden lg:block">
              {{ formatUpdatedAt(product.updated_at) }}

            </div>
            <div class="w-20 h-10 text-black hover:text-black justify-between  flex mr-8 ml-2 gap-3">
              <div @click.prevent="EditProduct(product.slug)"
                class="bg-[#20D0FF] p-2 rounded-lg transition-transform hover:scale-103 ease-in-out duration-300 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-5 h-5 text-white">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
              </div>
              <div @click="deleteModal(product.slug)"
                class="bg-red-500 p-2 rounded-lg transition-transform hover:scale-103 ease-in-out duration-300 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-5 h-5 text-white">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>

              </div>


            </div>
          </li>
        </ul>

      </div>


    </div>

    <div v-if="openmodal" id="popup-modal" tabindex="-1" class="
    transition-opacity duration-800 
    z-50  p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full"
      :class="{ 'opacity-100 pointer-events-auto': openmodal, 'opacity-0 pointer-events-none': !openmodal }">
      <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
          <button @click="ToggleModal" type="button"
            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
            data-modal-hide="popup-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
          <div class="p-6 text-center">
            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this
              product?</h3>
            <button @click.prevent="deleteProduct" data-modal-hide="popup-modal" type="button"
              class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
              Yes, I'm sure
            </button>
            <button @click="ToggleModal" data-modal-hide="popup-modal" type="button"
              class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No,
              cancel</button>

          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<style>
.rotate-svg:hover .now-rotate-svg {
  transform: rotate(-90deg);
}

/* Hide the scroll bar */
.scrollbar::-webkit-scrollbar {
  width: 0.2em;
  background-color: transparent;
}




/* Optional: Add a custom color for the thumb */
.scrollbar::-webkit-scrollbar-thumb {
  background-color: silver;
}

/* Optional: Add hover styles for the thumb */
.scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: slategray;
}

#popup-modal {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  backdrop-filter: blur(1px);
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>