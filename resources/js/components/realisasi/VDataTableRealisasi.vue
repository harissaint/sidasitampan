<template>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6>List Data Master Realisasi</h6>
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

import ModalAdd from './ModalAddRealisasi.vue';
import FormAdd from './FormAddRealisasi.vue';

DataTable.use(DataTablesCore);

let rows = [];

const columns = [{
    data: 'nama_skpd',
    title: 'No',
    render: function (data, type, row, meta) {
        return meta.row + 1;
    },
    orderable: false,
}, {
    data: 'tahun',
    title: 'Tahun',
}, {
    data: 'kode_skpd',
    title: 'SKPD',
    render: function (data, type, row, meta) {
        return data + ' - ' + row.nama_skpd;
    },
    searchable: true,
}, {
    data: 'updated_at',
    title: 'Update Terakhir',
    render: function (data, type, row, meta) {
        // format date tgl/bln/tahun hh:mm:ss
        let date = new Date(data);
        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();
        let hour = date.getHours();
        let minute = date.getMinutes();
        let second = date.getSeconds();
        return day + '/' + month + '/' + year + ' ' + hour + ':' + minute + ':' + second;
    },    
}, {
    data: 'jumlah',
    title: 'Total Realisasi',
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
                ajax: route('master.realisasi.index'),
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
            axios.post(route('master.realisasi.store'), formData, config)
                .then(response => {
                    // console.log(response.data);
                    // reload datatable
                    $('#dt-table').DataTable().ajax.reload();
                    this.toast.success("Data berhasil ditambahkan");
                })
                .catch(error => {
                    console.log(error);
                    if(error.response.status == 422)
                    {
                        this.toast.error(error.response.data.message);
                    }
                    else
                    {
                        console.log(error);
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