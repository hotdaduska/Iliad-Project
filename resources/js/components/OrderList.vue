<template>
    <v-container class="mt-5">
        <v-row justify="center">
            <v-col cols="12" sm="8">
                <v-btn color="red" class="mr-4" @click="showAddOrderModal = true">
                    Add Order
                </v-btn>

                <v-btn color="red" class="mr-4" @click="showAddProductModal = true">
                    Add Product
                </v-btn>

                <v-btn color="primary" @click="toggleFilters">
                    Filters
                </v-btn>

                <!-- Filter Box -->
                <v-expand-transition>
                    <div v-if="showFilters" class="mt-3 mb-6">
                        <v-card>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="6">
                                        <v-text-field v-model="filters.name" label="Filter by Name" outlined />
                                        <v-text-field v-model="filters.description" label="Filter by Description" outlined />
                                    </v-col>

                                    <v-col cols="6">
                                        <VueDatePicker
                                            v-model="date"
                                            :range="{ partialRange: false }"
                                            :teleport="true"
                                            @update:modelValue="setDateRange"
                                        />
                                        <v-row class="d-flex justify-center mt-3">
                                            <v-btn @click="setQuickDateRange('lastDay')" class="mr-5">Last Day</v-btn>
                                            <v-btn @click="setQuickDateRange('lastWeek')" class="mr-5">Last Week</v-btn>
                                            <v-btn @click="setQuickDateRange('lastMonth')" class="mr-5">Last Month</v-btn>
                                            <v-btn @click="setQuickDateRange('lastYear')">Last Year</v-btn>
                                        </v-row>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col class="d-flex justify-space-between">
                                        <v-btn class="mr-2" color="red" @click="searchAndClose">Search</v-btn>
                                        <v-btn v-if="filtersApplied" color="grey darken-1" @click="resetAndClose">Reset Filters</v-btn>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </div>
                </v-expand-transition>

                <!-- Loading -->
                <v-progress-linear v-if="loading" indeterminate color="red"></v-progress-linear>

                <!-- Data Table -->
                <v-data-table :headers="headers" :items="orders" class="elevation-1" item-value="id" density="comfortable" next-icon="mdi-chevron-right-circle" prev-icon="mdi-chevron-left-circle" rounded="circle" :items-per-page="20">
                    <template v-slot:header.name>
                        <span class="text-center d-block font-bold">Name</span>
                    </template>
                    <template v-slot:header.description>
                        <span class="text-center d-block font-bold">Description</span>
                    </template>
                    <template v-slot:header.order_date>
                        <span class="text-center d-block font-bold">Date</span>
                    </template>

                    <!-- Table Data -->
                    <template v-slot:item.name="{ item }">
                        <span class="text-center d-block">{{ item.name }}</span>
                    </template>
                    <template v-slot:item.description="{ item }">
                        <span 
                            class="description-hover text-center d-block" 
                            @mouseenter="showTooltip(item.description, $event)"
                            @mouseleave="hideTooltip"
                        >
                            {{ item.description.slice(0, 25) }}{{ item.description.length > 25 ? '...' : '' }}
                        </span>

                        <!-- Tooltip Element -->
                        <div 
                            v-if="tooltip.visible" 
                            :style="{ top: tooltip.top + 'px', left: tooltip.left + 'px', maxWidth: '300px' }" 
                            class="custom-tooltip"
                        >
                            {{ tooltip.content }}
                        </div>
                    </template>
                    <template v-slot:item.order_date="{ item }">
                        <span class="text-center d-block">{{ item.order_date }}</span>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <div class="text-right mt-3">
                            <v-btn color="red" class="mr-3" @click="viewOrder(item.id)">View</v-btn>
                            <v-btn color="grey darken-1" @click="confirmDelete(item.id)">Delete</v-btn>
                        </div>
                    </template>
                </v-data-table>

                <v-dialog v-model="dialog" max-width="290">
                    <v-card>
                        <v-card-title class="headline">Confirm Delete</v-card-title>
                        <v-card-text>Are you sure you want to delete this order?</v-card-text>
                        <v-card-actions>
                            <v-btn color="green darken-1" text @click="dialog = false">Cancel</v-btn>
                            <v-btn color="red darken-1" text @click="deleteOrder">Confirm</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <!-- Add Order Modal -->
                <v-dialog v-model="showAddOrderModal" max-width="600px">
                    <v-card>
                        <v-card-title>Add New Order</v-card-title>
                        <v-card-text>
                            <OrderForm 
                                ref="orderForm" 
                                :products="products"
                                @order-saved="fetchOrders" 
                                @close-modal="showAddOrderModal = false" 
                            />
                        </v-card-text>
                        <v-card-actions class="d-flex justify-space-between">
                            <v-btn color="warning" @click="showAddOrderModal = false">Close</v-btn>
                            <v-btn color="success" @click="$refs.orderForm.saveOrder()">Save Order</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <!-- Add Product Modal -->
                <v-dialog v-model="showAddProductModal" max-width="600px">
                    <v-card>
                        <v-card-title>Add New Product</v-card-title>
                        <v-card-text>
                            <ProductForm 
                                ref="productForm" 
                                :products="products"
                                @product-saved="fetchOrders" 
                                @close="showAddProductModal = false"
                            />
                        </v-card-text>
                        <v-card-actions class="d-flex justify-space-between">
                            <v-btn color="warning" @click="showAddProductModal = false">Close</v-btn>
                            <v-btn color="success" @click="$refs.productForm.saveProduct()">Save Product</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import VueDatePicker from '@vuepic/vue-datepicker';
import OrderForm from './OrderForm.vue';
import ProductForm from './ProductForm.vue';
import '@vuepic/vue-datepicker/dist/main.css';

const showAddOrderModal = ref(false);
const showAddProductModal = ref(false);

const date = ref(null);
const orders = ref([]);
const products = ref([]);
const filters = reactive({ name: "", description: "", order_date_from: null, order_date_to: null, order_date: null });
const filtersApplied = ref(false);
const headers = ref([
    { text: "Name", value: "name" },
    { text: "Description", value: "description" },
    { text: "Order Date", value: "order_date" },
    { text: "Actions", value: "actions", sortable: false },
]);
const loading = ref(false);
const loadingProducts = ref(false);
const dialog = ref(false);
const orderIdToDelete = ref(null);
const showFilters = ref(false);

const router = useRouter();

const fetchOrders = async () => {
    loading.value = true;
    const isDateRangeApplied = filters.order_date_from && filters.order_date_to;
    const isExactDateApplied = filters.order_date;

    filtersApplied.value = filters.name || filters.description || isDateRangeApplied || isExactDateApplied;

    const params = {
        name: filters.name,
        description: filters.description,
    };

    if (isDateRangeApplied) {
        params['order_date_from'] = filters.order_date_from;
        params['order_date_to'] = filters.order_date_to;
    } else if (isExactDateApplied) {
        params['order_date'] = filters.order_date;
    }

    try {
        const response = await axios.get("http://127.0.0.1:8000/api/orders", { params });
        orders.value = response.data;
        await fetchProducts();
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const fetchProducts = async () => {
    loadingProducts.value = true;
    try {
        const response = await axios.get("http://127.0.0.1:8000/api/products");
        products.value = response.data;
    } catch (error) {
        console.error("Error fetching products:", error);
    } finally {
        loadingProducts.value = false;
    }
};

const resetFilters = () => {
    filters.name = "";
    filters.description = "";
    filters.order_date_from = null;
    filters.order_date_to = null;
    filters.order_date = null;
    date.value = null;
    filtersApplied.value = false;
    fetchOrders();
};

const viewOrder = (id) => {
    router.push({ name: 'OrderDetails', params: { id } });
};

const confirmDelete = (id) => {
    orderIdToDelete.value = id;
    dialog.value = true;
};

const deleteOrder = async () => {
    loading.value = true;
    try {
        await axios.delete(`http://127.0.0.1:8000/api/orders/${orderIdToDelete.value}`);
        dialog.value = false;
        fetchOrders();
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const setQuickDateRange = (range) => {
    const today = new Date();
    let startDate;

    switch (range) {
        case 'lastDay':
            startDate = new Date(today.setDate(today.getDate() - 1));
            break;
        case 'lastWeek':
            startDate = new Date(today.setDate(today.getDate() - 7));
            break;
        case 'lastMonth':
            startDate = new Date(today.setMonth(today.getMonth() - 1));
            break;
        case 'lastYear':
            startDate = new Date(today.setFullYear(today.getFullYear() - 1));
            break;
    }

    const endDate = new Date();
    date.value = [startDate, endDate];
    setDateRange([startDate, endDate]);
};

const setDateRange = (selectedDates) => {
    if (selectedDates && selectedDates.length === 2) {
        const [startDate, endDate] = selectedDates;
        if (startDate && endDate) {
            const formattedStartDate = startDate.toISOString().split('T')[0];
            const formattedEndDate = endDate.toISOString().split('T')[0];
            filters.order_date_from = formattedStartDate;
            filters.order_date_to = formattedEndDate;
            filters.order_date = null;
        }
    } else if (selectedDates && selectedDates.length === 1) {
        const selectedDate = selectedDates[0];
        if (selectedDate) {
            const formattedDate = selectedDate.toISOString().split('T')[0];
            filters.order_date = formattedDate;
            filters.order_date_from = null;
            filters.order_date_to = null;
        }
    } else {
        filters.order_date_from = null;
        filters.order_date_to = null;
        filters.order_date = null;
    }
};

const toggleFilters = () => {
    showFilters.value = !showFilters.value;
};

const searchAndClose = () => {
    fetchOrders();
    toggleFilters();
};

const resetAndClose = () => {
    resetFilters();
    toggleFilters();
};

onMounted(fetchOrders);
</script>

<script>
export default {
    data() {
        return {
            tooltip: {
                visible: false,
                content: '',
                top: 0,
                left: 0
            }
        };
    },
    methods: {
        showTooltip(content, event) {
            this.tooltip.content = content;

            let tooltipTop = event.clientY - 10;
            let tooltipLeft = event.clientX + 10;

            const tooltipWidth = 300;
            const screenWidth = window.innerWidth;
            const screenHeight = window.innerHeight;
            
            if (tooltipLeft + tooltipWidth > screenWidth) {
                tooltipLeft = screenWidth - tooltipWidth - 10;
            }

            if (tooltipTop < 0) {
                tooltipTop = 10;
            }

            this.tooltip.top = tooltipTop;
            this.tooltip.left = tooltipLeft;
            this.tooltip.visible = true;
        },
        hideTooltip() {
            this.tooltip.visible = false;
        }
    }
};
</script>

<style scoped>
h1 {
    font-family: "Roboto", sans-serif;
    font-weight: bold;
}
.v-text-field,
.v-btn {
    margin-bottom: 15px;
}
.v-btn {
    text-transform: uppercase;
}
.text-center {
    text-align: center;
}
.d-block {
    display: block;
    width: 100%;
    text-align: center;
}
.font-bold {
    font-weight: bold;
}
.description-hover {
    cursor: pointer;
    position: relative;
}

.description-hover {
    cursor: pointer;
    position: relative;
}

.custom-tooltip {
    position: fixed;
    background-color: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 5px;
    border-radius: 3px;
    white-space: normal;
    word-wrap: break-word;
    max-width: 400px;
    z-index: 99999;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.v-selection-control__input::before {
    border-radius: 100% !important;
    background-color: grey !important;
    opacity: 0.5 !important;
    pointer-events: none !important;
}

</style>
