<template>
    <div>
        <form @submit.prevent="submitForm" id="form-add" class="row g-3">
            <div class="col-md-12">
                <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" v-model="name" required>
            </div>
            <div class="col-md-12">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" v-model="email" required>
            </div>
            <div class="col-md-12">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" v-model="password" required>
            </div>
            <div class="col-md-12">
                <label for="group_id" class="form-label">Group <span class="text-danger">*</span></label>
                <select class="form-select" id="group_id" v-model="group_id" required>
                    <option value="" selected hidden>Pilih Group</option>
                    <option v-for="group in groups" :key="group.id" :value="group.id">{{ group.nama }}</option>
                </select>
            </div>
            <div class="col-md-12 d-none">
                <label for="skpd_id" class="form-label">SKPD <span class="text-danger">*</span></label>
                <select class="form-select" id="skpd_id" v-model="skpd_id">
                    <option value="" selected hidden>Pilih SKPD</option>
                    <option v-for="skpd in skpds" :key="skpd.id" :value="skpd.id">{{ skpd.nama }}</option>
                </select>
            </div>
            <div class="col-md-12">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="status" v-model="status" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
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
            email: '',
            password: '',
            group_id: '',
            status: '',
            groups: [],
            skpds: [],
            group: {},
            skpd_id: '',
        };
    },
    mounted() {
        let el = this;
        this.fetchData();

        // add event listener
        $('#form-add').on('change', '#group_id', function () {
            // get val
            let val = $(this).val();
            el.getDataGroup(val, $(this));
            // console.log(el.group);
        });
    },
    methods: {
        submitForm() {
            const formData = {
                name: this.name,
                email: this.email,
                password: this.password,
                group_id: this.group_id,
                skpd_id: this.skpd_id,
                status: this.status,
            };
            this.$emit('addData', formData);
            this.resetForm();
            // Close the modal here
            this.$parent.close();
        },
        resetForm() {
            this.name = '';
            this.email = '';
            this.password = '';
            // this.group_id = '';
            this.status = '';
            this.skpd_id = '';
        },
        fetchData() {
            axios.get(route('api.master.group.index'))
                .then(response => {
                    // console.log(response.data.data);
                    this.groups = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });

            axios.get(route('api.master.skpd.index'))
                .then(response => {
                    // console.log(response.data.data);
                    this.skpds = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getDataGroup(id, elSelect) {
            axios.get(route('api.master.group.show', id))
                .then(response => {
                    this.group = response.data.data;
                    // check if SKPD
                    if(this.group.nama == 'SKPD'){
                        // this parent remove d-none
                        elSelect.parent().next().removeClass('d-none');
                    }else{
                        // this parent remove d-none
                        elSelect.parent().next().addClass('d-none');
                    }
                })
                .catch(error => {
                    console.log(error);
                    return null;
                });
        }
    },
};
</script>  