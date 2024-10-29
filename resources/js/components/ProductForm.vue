<template>
    <v-form @submit.prevent="saveProduct" ref="form">
        <!-- Loading -->
        <v-progress-linear v-if="loading" indeterminate color="red"></v-progress-linear>

        <v-text-field
            v-model="product.name"
            :rules="[rules.required, rules.minLength]"
            label="Product Name"
            required
        ></v-text-field>

        <v-text-field
            v-model="product.price"
            :rules="[rules.required, rules.isNumber]"
            label="Product Price"
            required
            type="number"
        ></v-text-field>

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
import axios from 'axios';

export default {
    data() {
        return {
            product: { name: '', price: '' },
            duplicateWarning: false,
            existingProducts: [],
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
        this.fetchExistingProducts();
    },
    methods: {
        async fetchExistingProducts() {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/products');
                this.existingProducts = response.data;
            } catch (error) {
                console.error("Error fetching existing products:", error);
            }
        },
        checkDuplicate() {
            return this.existingProducts.some(product => 
                product.name === this.product.name && 
                parseFloat(product.price) === parseFloat(this.product.price)
            );
        },
        async saveProduct() {
            if (!this.$refs.form.validate()) return;
            // Fetch latest products before checking for duplicates
            await this.fetchExistingProducts();

            // Check for duplicates
            if (this.checkDuplicate()) {
                this.duplicateWarning = true;
                return;
            }

            const newProduct = {
                name: this.product.name,
                price: parseFloat(this.product.price),
            };

            const url = `http://127.0.0.1:8000/api/products`;
            this.loading = true;
            
            try {
                await axios.post(url, newProduct);
                this.$emit('product-saved');
                this.resetForm();
                console.log("Product saved successfully:", newProduct);
                this.$emit('close');
            } catch (error) {
                console.error('Error saving product:', error.response.data);
                alert('An error occurred while saving the product.');
            } finally {
                this.loading = false;
            }
        },
        resetForm() {
            this.product = { name: '', price: '' };
        }
    },
};
</script>
