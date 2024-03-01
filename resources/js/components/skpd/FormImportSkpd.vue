<template>
    <div>
        <form @submit.prevent="submitForm" class="row g-3">
            <div class="col-md-12">
                <label for="file" class="form-label">File <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="file" v-on:change="file = $event.target.files[0]" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
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
            file: '',
        };
    },
    methods: {
        submitForm() {
            const formData = {
                file: this.file,
            };
            this.$emit('importData', formData);
            this.resetForm();
            // Close the modal here
            this.$parent.close();
        },
        resetForm() {
            this.file = '';
        },
    },
};
</script>  