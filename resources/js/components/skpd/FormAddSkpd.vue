<template>
    <div>
        <form @submit.prevent="submitForm" class="row g-3">  
            <div class="col-md-12">
                <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="kode" v-model="kode" required>
            </div>
            <div class="col-md-12">
                <label for="inputName" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="inputName" v-model="name" required>
            </div>
            <div class="col-md-12">
                <label for="nip" class="form-label">NIP Kepala <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nip" v-model="nip_kepala" required>
            </div>
            <div class="col-md-12">
                <label for="nama_kepala" class="form-label">Nama Kepala <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_kepala" v-model="nama_kepala" required>
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
            name: '',
            nip_kepala: '',
            nama_kepala: '',
        };
    },
    methods: {       
        submitForm() {
            const formData = {              
                kode: this.kode,
                nama: this.name,
                nip_kepala: this.nip_kepala,
                nama_kepala: this.nama_kepala,
            };            
            this.$emit('addData', formData);
            this.resetForm();
            // Close the modal here
            this.$parent.close();
        },
        resetForm() {
            this.kode = '';
            this.name = '';
            this.nip_kepala = '';
            this.nama_kepala = '';
        },
    },
};
</script>  