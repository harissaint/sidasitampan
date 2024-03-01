<template>
    <div>
        <form @submit.prevent="submitForm" class="row g-3">  
            <div class="col-md-12">
                <label for="inputName" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="inputName" v-model="name" required>
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
        };
    },
    methods: {       
        submitForm() {
            const formData = {              
                nama: this.name,
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