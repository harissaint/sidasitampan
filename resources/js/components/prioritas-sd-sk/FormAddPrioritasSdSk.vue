<template>
    <div>
        <form @submit.prevent="submitForm" class="row g-3">  
            <div class="col-md-12">
                <label for="inputName" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="inputName" v-model="name" required>
            </div>
            <div class="col-md-12">
                <label for="tahapan_id" class="form-label">Sumber Dana <span class="text-danger">*</span></label>
                <select class="form-select" id="tahapan_id" v-model="tahapan_id" @change="fetchData" required>
                    <option v-for="tahap in tahapans" :key="tahap.id" :value="tahap.id">{{ tahap.nama }}</option>
                </select>
            </div>
            <!-- Add more form fields here -->
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            name: '',
            tahapans: [],
            tahapan_id: '',
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {       
        submitForm() {
            const formData = {              
                nama: this.name,
                tahun:this.tahun,
                tahapan_id: this.tahapan_id,
            };            
            this.$emit('addData', formData);
            this.resetForm();
            // Close the modal here
            this.$parent.close();
        },
        resetForm() {
            this.name = '';
            this.tahapan_id = '';
            // Reset other form field data here
        },
        fetchData() {
            axios.get(route('api.master.tahapan.index'))
                .then(response => {
                    // console.log(response.data.data);
                    this.tahapans = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
    },
};
</script>  