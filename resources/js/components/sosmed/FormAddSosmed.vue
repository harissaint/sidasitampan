<template>
    <div>
        <form @submit.prevent="submitForm" class="row g-3">  
            <div class="col-md-12">
                <label for="inputName" class="form-label">Link <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="inputName" v-model="link" required>
            </div>
            <div class="col-md-12">
                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select class="form-select" v-model="category" required>
                    <option value="">Pilih Kategori</option>
                    <option value="SIDASI TAMPAN">SIDASI TAMPAN</option>
                    <option value="SIPD PENGANGGARAN">SIPD PENGANGGARAN</option>
                    <option value="SIPD-RI PENGANGGARAN">SIPD-RI PENGANGGARAN</option>
                    <option value="SIPD-RI PENATAUSAHAAN">SIPD-RI PENATAUSAHAAN</option>
                    <option value="Lain-lain">Lain-lain</option>
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
export default {
    data() {
        return {
            link: '',
            category: ''
        };
    },
    methods: {       
        submitForm() {
            const formData = {              
                link: this.link,
                category: this.category
            };            
            this.$emit('addData', formData);
            this.resetForm();
            // Close the modal here
            this.$parent.close();
        },
        resetForm() {
            this.link = '';
            this.year = '';
            this.description = '';
            // Reset other form field data here
        },
    },
};
</script>  