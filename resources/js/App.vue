<template>
    <v-app style="background-color: #e3e3e3fa !important;">
        <div style="text-align: center; padding: 20px;">
            <img src="@/assets/iliad-logo.png" alt="Logo" style="height: 150px;     "> 
        </div>
    <v-main>
        <router-view></router-view>
    </v-main>
    </v-app>
</template>

<script>
export default {
    name: "App",
};
</script>

<style>
.dropdown-select {
    position: relative;
}

.vs__dropdown-menu {
    position: absolute; /* Make the dropdown menu absolute */
    bottom: 100%; /* Position it above the toggle */
    left: 0; /* Align it to the left */
    z-index: 10; /* Ensure it appears above other content */
    width: 100%; /* Make it the same width as the toggle */
    background: white; /* Set a background color for visibility */
    border: 1px solid #ccc; /* Optional: add a border */
    border-radius: 4px; /* Optional: add rounded corners */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Optional: add some shadow */
}

.dropdown-select .vs__dropdown-toggle {
    width: 100%;
    border: 1px solid #ccc; /* Border around the dropdown */
    border-radius: 4px; /* Rounded corners */
    padding: 10px; /* Padding for better spacing */
    background-color: #fff; /* White background */
    cursor: pointer; /* Pointer cursor */
    display: flex; /* Flexbox to align items */
    align-items: center; /* Center items vertically */
    justify-content: space-between; /* Space between elements */
}

.dropdown-select .vs__selected-options {
    flex: 1; /* Allow it to take available space */
    display: flex; /* Flexbox for search input */
    align-items: center; /* Center vertically */
}

.dropdown-select .vs__search {
    border: none; /* Remove border */
    outline: none; /* Remove outline */
    flex: 1; /* Allow input to take available space */
    padding: 0; /* Remove padding */
    margin-right: 10px; /* Space between input and indicator */
}

.dropdown-select .vs__actions {
    display: flex; /* Flexbox for action buttons */
    align-items: center; /* Center items vertically */
}

.dropdown-select .vs__dropdown-menu {
    position: absolute; /* Position the dropdown options */
    z-index: 1000; /* Ensure it overlays other elements */
    width: 100%; /* Match the width of the select */
    border: 1px solid #ccc; /* Border for dropdown menu */
    border-radius: 4px; /* Rounded corners */
    background-color: #fff; /* White background */
    max-height: 200px; /* Maximum height */
    overflow-y: auto; /* Scrollable if too many options */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

.dropdown-select .vs__dropdown-option {
    padding: 10px; /* Padding for options */
    cursor: pointer; /* Pointer cursor */
}

.dropdown-select .vs__dropdown-option:hover {
    background-color: #f0f0f0; /* Highlight on hover */
}

.dropdown-select .vs__spinner {
    display: none; /* Hide loading spinner by default */
}

.v-select .vs__dropdown-option.selected {
    background-color: lightgray; /* Light gray background for selected options */
}

.v-select .vs__dropdown-menu {
    max-height: 200px; /* Limit the dropdown height */
    overflow-y: auto; /* Scrollable if options exceed max-height */
}

.v-data-table .v-icon {
    color: red;  /* Change icon color */
    font-size: 24px;  /* Adjust icon size */
}

</style>