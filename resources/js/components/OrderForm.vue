<template>
    <v-form @submit.prevent="saveOrder" ref="form">
        <!-- Order Name -->
        <v-text-field
            v-model="order.name"
            :rules="[rules.required, rules.minLength]"
            label="Order Name"
            required
        ></v-text-field>

        <!-- Order Description -->
        <v-textarea
            v-model="order.description"
            :rules="[rules.required, rules.minLength]"
            label="Description"
            required
        ></v-textarea>

        <!-- Order Date (Using VueDatePicker) -->
        <VueDatePicker
            v-model="order.order_date"
            :format="'yyyy-MM-dd'"
            :teleport="true"
            :editable="false"
            :range="false"
            label="Order Date"
            :rules="[rules.required]"
            required
            class="mb-5"
        />

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

        <!-- Quantity Input -->
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

        <!-- List of Added Products -->
        <div v-if="addedProducts.length">
            <h4>Added Products:</h4>
            <ul>
                <li class="mt-1 mb-2" v-for="(product, index) in addedProducts" :key="index" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>{{ product.name }} (Quantity: {{ product.quantity }})</span>
                    <v-btn small @click="removeProduct(index)" color="red">Remove</v-btn>
                </li>
            </ul>
        </div>
    </v-form>
</template>

<script>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';

export default {
    components: { VueDatePicker },

    props: {
        existingOrder: {
            type: Object,
            default: () => ({
                name: '',
                description: '',
                order_date: '',
                products: [], // Assuming you might pass products for editing
            }),
        },
        products: { // Add products prop
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            order: { ...this.existingOrder }, // Clone the existingOrder
            selectedProduct: {}, // This will hold the selected product object
            quantity: 1, // Default quantity
            addedProducts: [], // Store added products
            isDropdownOpen: false, // State to manage dropdown visibility
            loading: false, // You can keep this if you need loading states elsewhere
            rules: {
                required: (value) => !!value || 'Required.',
                minLength: (value) =>
                    (value && value.length >= 3) || 'Minimum 3 characters.',
                atLeastOne: (value) => 
                    (this.addedProducts.length > 0) || 'At least one product must be selected.',
            },
        };
    },
    computed: {
        productOptions() {
            return this.products.map((product) => ({
                id: product.id,
                name: product.name,
            }));
        },
    },
    methods: {
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen; // Toggle dropdown state
        },
        selectProduct(product) {
            this.selectedProduct = product; // Set the selected product
            this.isDropdownOpen = false; // Close dropdown after selection
        },
        addProduct() {
            if (!this.selectedProduct.id || this.quantity < 1) {
                alert('Please select a product and enter a valid quantity.');
                return; // Ensure a product is selected and quantity is valid
            }
            // Add the selected product with its quantity
            this.addedProducts.push({
                id: this.selectedProduct.id,
                name: this.selectedProduct.name,
                quantity: this.quantity,
            });
            // Reset selection and quantity
            this.selectedProduct = {};
            this.quantity = 1; // Reset quantity to default
        },
        removeProduct(index) {
            this.addedProducts.splice(index, 1);
        },
        saveOrder() {
            if (!this.$refs.form.validate()) return; // Validate form

            // Additional validation for the dropdown
            if (this.addedProducts.length === 0) {
                alert(this.rules.atLeastOne());
                return; // Show alert if no products are selected
            }

            this.order.order_date = new Date(this.order.order_date).toISOString().split('T')[0];
            this.order.products = this.addedProducts; // Update the order's products

            const url = this.existingOrder.id
                ? `http://127.0.0.1:8000/api/orders/${this.existingOrder.id}`
                : `http://127.0.0.1:8000/api/orders`;
            
            axios({
                method: this.existingOrder.id ? 'put' : 'post',
                url,
                data: this.order,
            })
            .then(() => {
                this.$emit('order-saved'); // Emit event after successful save
                this.resetForm(); // Reset form after saving
                this.$emit('close-modal');
            })
            .catch((error) => {
                console.error('Error saving order:', error.response.data); // Log the error details
                alert('An error occurred while saving the order.'); // Provide user feedback
            });
        },
        resetForm() {
            this.order = { name: '', description: '', order_date: '', products: [] }; // Reset order form
            this.selectedProduct = {}; // Clear selected product
            this.quantity = 1; // Reset quantity
            this.addedProducts = []; // Clear added products
        },
        handleClickOutside(event) {
            const dropdown = this.$el.querySelector('.dropdown-select');
            if (dropdown && !dropdown.contains(event.target)) {
                this.isDropdownOpen = false; // Close dropdown if clicked outside
            }
        },
    },
    mounted() {
        // Close dropdown when clicking outside
        window.addEventListener('click', this.handleClickOutside);
    },
    beforeDestroy() {
        // Clean up the event listener when component is destroyed
        window.removeEventListener('click', this.handleClickOutside);
    }
};
</script>
