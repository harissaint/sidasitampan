<template>
    <div>
        <form @submit.prevent="submitForm" class="row g-3">  
            <div class="col-md-6">
                <label for="inputName" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="inputName" v-model="name" required>
            </div>
            <div class="col-md-6">
                <label for="inputYear" class="form-label">Tahun <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="inputYear" v-model="year" required>
            </div>
            <div class="col-12">
                <label for="inputDescription" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                <textarea class="form-control" id="inputDescription" v-model="description" rows="3" required></textarea>
            </div>
            <div class="col-12">
                <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="is_active" v-model="is_active" required>
                    <option value="1">Aktif</option>
                    <option value="0" selected>Tidak Aktif</option>
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
            name: '',
            year: '',
            description: '',
            is_active: '0',
            // Add more form fields here
        };
    },
    methods: {       
        submitForm() {
            const formData = {              
                nama: this.name,
                tahun: this.year,
                keterangan: this.description,
                is_active: this.is_active
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
            this.is_active = '0';
            // Reset other form field data here
        },
    },
};
</script>  