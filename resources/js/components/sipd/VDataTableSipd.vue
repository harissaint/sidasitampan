<template>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6>List Data Master SIPD</h6>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal" v-if="!isLoading">
                <i class="bx bx-plus me-0"></i>
            </button>

            <button type="button" class="btn btn-primary" disabled v-else>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
        </div>
    </div>

    <ModalAdd>
        <FormAdd @addData="addData"></FormAdd>
    </ModalAdd>

    <!-- <div class="table-responsive"> -->
    <DataTable id="dt-table" :columns="columns" :options="options" class="table table-bordered table-hover" style="width:100%" />
    <!-- </div> -->
</template>

<script>
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import 'datatables.net-fixedcolumns-dt';
import { useToast } from "vue-toastification";
import axios from 'axios';
import $ from 'jquery'; // Import jQuery if you haven't already

import ModalAdd from './ModalAddSipd.vue';
import FormAdd from './FormAddSipd.vue';

DataTable.use(DataTablesCore);

let rows = [];

const columns = [{
    data: 'id',
    title: 'No',
    render: function (data, type, row, meta) {
        return meta.row + 1;
    },
}, {
    data: 'tahapan.nama',
    title: 'Tahapan',
}, {
    data: 'kode_urusan',
    title: 'Kode Urusan',
}, {
    data: 'nama_urusan',
    title: 'Nama Urusan',
}, {
    data: 'kode_bidang_urusan',
    title: 'Kode Bidang Urusan',
}, {
    data: 'nama_bidang_urusan',
    title: 'Nama Bidang Urusan',
}, {
    data: 'kode_skpd',
    title: 'Kode SKPD',
}, {
    data: 'nama_skpd',
    title: 'Nama SKPD',
}, {
    data: 'kode_program',
    title: 'Kode Program',
}, {
    data: 'nama_program',
    title: 'Nama Program',
}, {
    data: 'kode_kegiatan',
    title: 'Kode Kegiatan',
}, {
    data: 'nama_kegiatan',
    title: 'Nama Kegiatan',
}, {
    data: 'kode_sub_kegiatan',
    title: 'Kode Sub Kegiatan',
}, {
    data: 'nama_sub_kegiatan',
    title: 'Nama Sub Kegiatan',
}, {
    data : 'kode_akun',
    title : 'Kode Akun',
}, {
    data : 'nama_akun',
    title : 'Nama Akun',
}, {
    data: 'nilai_rincian',
    title: 'Nilai Rincian',
    render: function (data, type, row, meta) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
    },
    className: 'text-end',
}];

export default {
    components: {
        DataTable,
        ModalAdd,
        FormAdd,
    },
    data() {
        return {
            isLoading: false,
            toast: useToast(),
            rows: rows,
            columns: columns,
            options: {
                fixedColumns: {
                    left: 0,
                    right: 1
                },
                processing: true,
                serverSide: true,
                ajax: route('master.sipd.index'),
                scrollCollapse: true,
                scrollX: true,
            }
        }
    },
    methods: {
        addData(formData) {
            // console.log(formData);
            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }
            this.isLoading = true;
            // store data
            axios.post(route('master.sipd.store'), formData, config)
                .then(response => {
                    // console.log(response.data);
                    // reload datatable
                    $('#dt-table').DataTable().ajax.reload();
                    this.toast.success("Data berhasil ditambahkan");
                })
                .catch(error => {
                    // console.log(error);
                    if(error.response.status == 422)
                    {
                        this.toast.error(error.response.data.message);
                    }
                    else
                    {
                        this.toast.error("Data gagal ditambahkan");
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
    },
}

</script>

<style>
@import 'datatables.net-bs5';
@import 'datatables.net-fixedcolumns-dt';
</style>