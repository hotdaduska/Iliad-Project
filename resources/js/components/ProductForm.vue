<template>
    <v-form @submit.prevent="saveProduct" ref="form">
        <!-- Loading Indicator -->
        <v-progress-linear v-if="loading" indeterminate color="red"></v-progress-linear>

        <!-- Product Name -->
        <v-text-field
            v-model="product.name"
            :rules="[rules.required, rules.minLength]"
            label="Product Name"
            required
        ></v-text-field>

        <!-- Product Price -->
        <v-text-field
            v-model="product.price"
            :rules="[rules.required, rules.isNumber]"
            label="Product Price"
            required
            type="number"
        ></v-text-field>

        <!-- Warning Message -->
        <v-alert
            v-if="duplicateWarning"
            type="warning"
            dismissible
            @close="duplicateWarning = false"
        >
            A product with the same name and price already exists.
        </v-alert>
    </v-form>
</template>

<script>
import axios from 'axios'; // Ensure you import axios if not already done

export default {
    data() {
        return {
            product: { name: '', price: '' }, // Product data
            duplicateWarning: false, // State to manage duplicate warning
            existingProducts: [], // Local array to store existing products
            loading: false,
            rules: {
                required: (value) => !!value || 'Required.',
                minLength: (value) => 
                    (value && value.length >= 3) || 'Minimum 3 characters.',
                isNumber: (value) => !isNaN(value) || 'Must be a number.',
            },
        };
    },
    mounted() {
        this.fetchExistingProducts(); // Fetch existing products on mount
    },
    methods: {
        async fetchExistingProducts() {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/products');
                // Assuming the response data is an array of products
                this.existingProducts = response.data;
                console.log("Fetched existing products:", this.existingProducts); // Log fetched products
            } catch (error) {
                console.error("Error fetching existing products:", error);
            }
        },
        checkDuplicate() {
            // Log the current product for debugging
            console.log("Checking for duplicate:", this.product);

            // Check for duplicates in the existingProducts array
            return this.existingProducts.some(product => 
                product.name === this.product.name && 
                parseFloat(product.price) === parseFloat(this.product.price) // Ensure both are floats
            );
        },
        async saveProduct() {
            if (!this.$refs.form.validate()) return; // Validate form

            // Log the state of the form before checking duplicates
            console.log("Product form state before save:", this.product);

            // Fetch the latest products before checking for duplicates
            await this.fetchExistingProducts(); // Ensure this call is awaited

            // Check for duplicates
            if (this.checkDuplicate()) {
                this.duplicateWarning = true; // Show warning if duplicate
                console.log("Duplicate detected, product will not be saved."); // Log duplicate warning
                return;
            }

            const newProduct = {
                name: this.product.name,
                price: parseFloat(this.product.price), // Ensure price is a number
            };

            const url = `http://127.0.0.1:8000/api/products`; // Adjust API endpoint as necessary
            this.loading = true;
            
            try {
                await axios.post(url, newProduct);
                this.$emit('product-saved'); // Emit event after successful save
                this.resetForm(); // Reset form after saving
                console.log("Product saved successfully:", newProduct); // Log success
                this.$emit('close');
            } catch (error) {
                console.error('Error saving product:', error.response.data); // Log the error details
                alert('An error occurred while saving the product.'); // Provide user feedback
            } finally {
                this.loading = false;
            }
        },
        resetForm() {
            this.product = { name: '', price: '' }; // Reset product form
        }
    },
};
</script>
