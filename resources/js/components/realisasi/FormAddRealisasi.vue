<template>
    <div>
        <form @submit.prevent="submitForm" class="row g-3" enctype="multipart/form-data">
            <div class="col-md-12">
                <label for="file" class="form-label">Import File <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="file" v-on:change="file = $event.target.files[0]"
                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                    required>
            </div>
            <div class="col-md-12">
                <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="tahun" v-model="tahun" required>
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
            file: '',
            tahun: '',
            tahapans: [],
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        submitForm() {
            // console.log(this.file);
            const formData = {
                file: this.file,
                tahun: this.tahun,
            };
            this.$emit('addData', formData);
            this.resetForm();
            // Close the modal here
            this.$parent.close();
        },
        resetForm() {
            this.name = '';
            this.tahun = '';
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