<template>
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

import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
// import ModalAdd from './ModalAddSipd.vue';
// import FormAdd from './FormAddSipd.vue';

DataTable.use(DataTablesCore);

let rows = [];

const columns = [{
    data: 'id',
    title: 'No',
    render: function (data, type, row, meta) {
        return meta.row + 1;
    },
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
    title: 'Nilai Rincian Belanja',
    render: function (data, type, row, meta) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
    },
    className: 'text-end',
}];

export default {
    props: {
        tahapan_id: {
            required: true,
        },
    },
    components: {
        DataTable,
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
                ajax: route('master.tahapan.show', this.tahapan_id),
                scrollCollapse: true,
                scrollX: true,
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, 'Semua']
                ],
                dom: 'Bfrtip',
                buttons: [
                    'pageLength', 'copy', 'csv',
                    {
                        extend: 'excelHtml5',
                        filename: 'Data Master SIPD',
                        title: 'Data Master SIPD',
                        messageTop: 'Data Master SIPD',
                        messageBottom: 'Tanggal: ' + new Date(),
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                    , 'pdf', 'print'
                ]
            }
        }
    },
}

</script>

<style>
@import 'datatables.net-bs5';
@import 'datatables.net-fixedcolumns-dt';
@import 'datatables.net-buttons-bs5';
</style>