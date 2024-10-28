<template>
    <v-container class="mt-5 w-50">
        <v-card>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <v-btn @click="goBack">Back</v-btn>
                <v-btn color="orange" @click="openEditOrderModal">Edit Order</v-btn>
            </div>
            <v-card-title>{{ order.name }}</v-card-title>
            <v-card-text>{{ order.description }}</v-card-text>
            <v-card-subtitle>Order Date: {{ order.order_date }}</v-card-subtitle>
            
            

            <v-progress-linear v-if="loading" indeterminate color="red"></v-progress-linear>

            <v-data-table
                v-if="Products.length > 0"  
                :headers="Headers"
                :items="Products"
                class="elevation-1"
                item-value="product_id" 
            >
                <template v-slot:header.name>
                    <span class="text-center d-block font-bold">Name</span>
                </template>
                <template v-slot:header.price>
                    <span class="text-center d-block font-bold">Price</span>
                </template>
                <template v-slot:header.quantity>
                    <span class="text-center d-block font-bold">Quantity</span>
                </template>
                <template v-slot:item.name="{ item }">
                    <span class="text-center d-block">{{ item.name }}</span>
                </template>
                <template v-slot:item.price="{ item }">
                    <span class="text-center d-block">{{ item.price }}</span>
                </template>
                <template v-slot:item.quantity="{ item }">
                    <span class="text-center d-block">{{ item.quantity }}</span>
                </template>
            </v-data-table>

            <!-- Edit Order Modal -->
            <v-dialog v-model="editDialog" max-width="500px">
                <v-card>
                    <v-card-title>Edit Order</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="editedOrder.name" label="Order Name"></v-text-field>
                        <v-textarea v-model="editedOrder.description" label="Order Description"></v-textarea>

                        <!-- Product Selection Section -->
                        <div class="dropdown-select" @click="toggleDropdown">
                            <div id="vs2__combobox" class="vs__dropdown-toggle" role="combobox" :aria-expanded="isDropdownOpen" aria-owns="vs2__listbox" aria-label="Search for option">
                                <div class="vs__selected-options">
                                    <span v-if="!selectedProduct.id">Select Product</span>
                                    <span>{{ selectedProduct.name }}</span>
                                </div>
                                <div class="vs__actions">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" role="presentation" class="vs__open-indicator">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.23017 7.20938C5.52875 6.92228 6.00353 6.93159 6.29063 7.23017L10 11.1679L13.7094 7.23017C13.9965 6.93159 14.4713 6.92228 14.7698 7.20938C15.0684 7.49647 15.0777 7.97125 14.7906 8.26983L10.5406 12.7698C10.3992 12.9169 10.204 13 10 13C9.79599 13 9.60078 12.9169 9.45938 12.7698L5.20938 8.26983C4.92228 7.97125 4.93159 7.49647 5.23017 7.20938Z" fill="#0F172A"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Loading Indicator -->
                            <v-progress-linear v-if="loading" indeterminate color="red" />

                            <ul v-if="isDropdownOpen && !loading" id="vs2__listbox" class="vs__dropdown-menu" role="listbox" tabindex="-1">
                                <li v-for="product in productOptions" :key="product.id" 
                                    :class="['vs__dropdown-option', { 'selected': selectedProduct.id === product.id }]" 
                                    @click.stop="selectProduct(product)">
                                    {{ product.name }}
                                </li>
                            </ul>
                        </div>

                        <!-- Quantity Input for new products -->
                        <v-text-field
                            v-model="quantity"
                            :rules="[rules.required]"
                            label="Quantity"
                            type="number"
                            required
                            min="1"
                            class="mt-3"
                        ></v-text-field>

                        <!-- Button to Add Another Product -->
                        <v-btn @click="addProduct" color="primary" class="mb-5">Add Product</v-btn>

                        <div v-if="addedProducts.length">
                            <h4>Added Products:</h4>
                            <ul>
                                <li class="mt-1 mb-2" v-for="(product, index) in addedProducts" :key="index">
                                    <span>{{ product.name }} - Quantity: </span>
                                    <v-text-field
                                        v-model="product.quantity"
                                        type="number"
                                        min="1"
                                        @input="updateAddedProductQuantity(product)"
                                    />
                                    <v-btn small @click="removeProduct(index)" color="red">Remove</v-btn>
                                </li>
                            </ul>
                        </div>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="primary" @click="saveChanges">Save Changes</v-btn>
                        <v-btn @click="closeEditModal">Close</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-card>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

// Define reactive state variables
const order = ref({});
const Products = ref([]);
const editedOrder = ref({ name: '', description: '' });
const originalAddedProducts = ref([]);
const addedProducts = ref([]); 
const removedProducts = ref([]); // Track removed products
const editDialog = ref(false);
const selectedProduct = ref({}); 
const quantity = ref(1); 
const isDropdownOpen = ref(false);
const loading = ref(false);
const Headers = ref([
    { text: 'Name', value: 'name' },
    { text: 'Price', value: 'price' },
    { text: 'Quantity', value: 'quantity' },
]);

const rules = {
    required: value => !!value || 'Required.',
};

const route = useRoute();
const router = useRouter();

// Fetch order details including products with quantity
const fetchOrderDetails = async () => {
    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/orders/${route.params.id}`);
        order.value = response.data;

        // Load existing products with quantities
        Products.value = Array.isArray(order.value.products)
            ? order.value.products.map(product => ({
                name: product.name,
                price: product.price,
                quantity: product.pivot ? product.pivot.quantity : 1,
                product_id: product.id 
            })) : [];

        addedProducts.value = Products.value.map(product => ({
            id: product.product_id,
            name: product.name,
            quantity: product.quantity 
        }));

        originalAddedProducts.value = JSON.parse(JSON.stringify(addedProducts.value));

        editedOrder.value = {
            name: order.value.name,
            description: order.value.description,
            order_date: order.value.order_date
        };

    } catch (error) {
        console.error('Error fetching order details:', error);
    }
};

onMounted(() => {
    fetchOrderDetails();
});

// Navigation methods
const goBack = () => {
    router.push({ name: 'OrderList' });
};

const openEditOrderModal = () => {
    editDialog.value = true;
};

const closeEditModal = () => {
    editDialog.value = false;
    addedProducts.value = JSON.parse(JSON.stringify(originalAddedProducts.value)); // Reset to original products
};

// Save changes made in the edit order modal
const saveChanges = async () => {
    loading.value = true;
    try {
        await axios.put(`http://127.0.0.1:8000/api/orders/${order.value.id}`, {
            name: editedOrder.value.name,
            description: editedOrder.value.description,
            order_date: editedOrder.value.order_date,
            products: addedProducts.value.map(product => ({ id: product.id, quantity: product.quantity })), // Send updated quantities
            removedProducts: removedProducts.value // Send removed products
        });
        removedProducts.value = []; // Clear removed products after save
        await fetchOrderDetails(); // Refresh order details
    } catch (error) {
        console.error('Error saving changes:', error);
    } finally {
        loading.value = false;
        editDialog.value = false; // Close the modal
    }
};

const addProduct = () => {
    if (!selectedProduct.value.id || quantity.value < 1) {
        alert('Please select a product and enter a valid quantity.');
        return;
    }

    const existingProductIndex = addedProducts.value.findIndex(product => product.id === selectedProduct.value.id);
    if (existingProductIndex === -1) {
        addedProducts.value.push({
            id: selectedProduct.value.id,
            name: selectedProduct.value.name,
            quantity: quantity.value
        });
    } else {
        addedProducts.value[existingProductIndex].quantity = quantity.value;
    }

    selectedProduct.value = {};
    quantity.value = 1;
};

// Update quantity for existing products
const updateAddedProductQuantity = (product) => {};

// Remove a product from the order
const removeProduct = (index) => {
    const productToRemove = addedProducts.value[index];
    removedProducts.value.push(productToRemove);  // Push to removedProducts
    addedProducts.value.splice(index, 1); // Remove from addedProducts
};

// Toggle the dropdown
const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

// Select a product from the dropdown
const selectProduct = (product) => {
    selectedProduct.value = product;
    isDropdownOpen.value = false;
};

// Load products for selection in dropdown
const productOptions = ref([]);
const fetchProductOptions = async () => {
    try {
        loading.value = true;
        const response = await axios.get('http://127.0.0.1:8000/api/products');
        productOptions.value = response.data;
    } catch (error) {
        console.error('Error fetching products:', error);
    } finally {
        loading.value = false;
    }
};

fetchProductOptions();
</script>
