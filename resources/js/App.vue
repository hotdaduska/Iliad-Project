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
    position: absolute;
    bottom: 100%;
    left: 0;
    z-index: 10;
    width: 100%;
    background: white;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.dropdown-select .vs__dropdown-toggle {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10px;
    background-color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.dropdown-select .vs__selected-options {
    flex: 1;
    display: flex;
    align-items: center;
}

.dropdown-select .vs__search {
    border: none;
    outline: none;
    flex: 1;
    padding: 0;
    margin-right: 10px;
}

.dropdown-select .vs__actions {
    display: flex;
    align-items: center;
}

.dropdown-select .vs__dropdown-menu {
    position: absolute;
    z-index: 1000;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    max-height: 200px;
    overflow-y: auto;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.dropdown-select .vs__dropdown-option {
    padding: 10px;
    cursor: pointer;
}

.dropdown-select .vs__dropdown-option:hover {
    background-color: #f0f0f0;
}

.dropdown-select .vs__spinner {
    display: none;
}

.v-select .vs__dropdown-option.selected {
    background-color: lightgray;
}

.v-select .vs__dropdown-menu {
    max-height: 200px;
    overflow-y: auto;
}
</style>