<template>
    <div>
        <form @submit.prevent="submitForm" class="row g-3">  
            <div class="col-md-12">
                <label for="inputName" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="inputName" v-model="name" required>
            </div>
            <div class="col-md-12">
                <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="tahun" v-model="tahun" required>
            </div>
            <div class="col-md-12">
                <label for="Keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                <textarea class="form-control" id="Keterangan" v-model="keterangan" required></textarea>
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
            name: '',
            tahun:'',
            keterangan: '',
        };
    },
    methods: {       
        submitForm() {
            const formData = {              
                nama: this.name,
                tahun:this.tahun,
                keterangan:this.keterangan
            };            
            this.$emit('addData', formData);
            this.resetForm();
            // Close the modal here
            this.$parent.close();
        },
        resetForm() {
            this.name = '';
            this.year = '';
            this.description = '';
            // Reset other form field data here
        },
    },
};
</script>  