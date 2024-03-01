<template>
    <div>
        <form @submit.prevent="submitForm" class="row g-3">  
            <div class="col-md-12">
                <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="kode" v-model="kode" required>
            </div>
            <div class="col-md-12">
                <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" v-model="nama" required>
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
export default {
    data() {
        return {
            kode: '',
            nama: '',
            tahun: '',
        };
    },
    methods: {       
        submitForm() {
            const formData = {              
                kode: this.kode,
                nama: this.nama,
                tahun: this.tahun,
            };            
            this.$emit('addData', formData);
            this.resetForm();
            // Close the modal here
            this.$parent.close();
        },
        resetForm() {
            this.kode = '';
            this.nama = '';
            this.tahun = '';
        },
    },
};
</script>  