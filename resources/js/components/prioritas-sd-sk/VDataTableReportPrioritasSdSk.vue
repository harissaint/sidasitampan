<template>
    <!-- <div class="table-responsive"> -->
    <DataTable id="dt-table" :columns="columns" :options="options" class="table table-bordered table-hover"
        style="width:100%" />
    <!-- </div> -->
</template>

<script>
import jszip from 'jszip';
import pdfmake from 'pdfmake';
// import DataTable from 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';

import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import 'datatables.net-fixedcolumns-dt';
import { useToast } from "vue-toastification";
import axios from 'axios';
import $ from 'jquery'; // Import jQuery if you haven't already

DataTable.use(DataTablesCore);

let rows = [];

const columns = [{
    data: 'sipd.kode_skpd',
    title: 'No',
    render: function (data, type, row, meta) {
        return meta.row + 1;
    },
}, {
    data: 'sipd.nama_skpd',
    title: 'SKPD',
    render: function (data, type, row, meta) {
        return row.sipd.kode_skpd + ' - ' + row.sipd.nama_skpd;
    },
}, {
    data: 'sipd.kode_sub_kegiatan',
    title: 'Sub Kegiatan',
    render: function (data, type, row, meta) {
        return row.sipd.kode_sub_kegiatan + ' - ' + row.sipd.nama_sub_kegiatan;
    },
}, {
    data: 'sipd.nilai_rincian',
    title: 'Anggaran',
    render: function (data, type, row, meta) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
    },
    className: 'text-end',
}, {
    data: 'sipd.nilai_rincian',
    title: 'Nilai',
    render: function (data, type, row, meta) {
        if(row.nilai == null){
            return `<input type="number" class="form-control nilai" id="inputNilai${row.id}" name="nilai" value="${data}" required>`;
        }else{
            return `<input type="number" class="form-control nilai" id="inputNilai${row.id}" name="nilai" value="${row.nilai}" required>`;
        }
    },
    className: 'text-end',
}];

export default {
    props: {
        group: {
            required: true,
        },
        skpd_nama: {
            required: true,
        },
        prioritas_id: {
            required: true,
        },
    },
    components: {
        DataTable
    },
    data() {
        return {
            skpds: [],
            tahapans: [],
            tahapan_id: '',
            skpd: '',
            toast: useToast(),
            rows: rows,
            columns: columns,
            options: {
                fixedColumns: {
                    left: 0,
                    right: 1
                },
                processing: false,
                serverSide: false,
                ajax: {
                    url: route('master.prioritas-sd-sk.report', this.prioritas_id),
                    type: 'GET',
                    data: {
                        tahapan_id: this.tahapan_id,
                        skpd: this.skpd
                    },
                    beforeSend: function (request) {
                        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                },
                scrollCollapse: true,
                scrollX: true,
                // download and page length options
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
    mounted() {
        let el = this;
        // add event listener
        $('#dt-table').on('change', '.nilai', function () {
            let id = $(this).attr('id');
            let value = $(this).val();
            let row = id.replace('inputNilai', '');
            let data = {
                id: row,
                nilai: value,
            };
            
            axios.post(route('master.prioritas-sd-sk.updatenilai', data))
                .then(response => {
                    el.toast.success(response.data.message);
                })
                .catch(error => {
                    console.log(error);
                    el.toast.error(error.response.data.message);
                });
        });
    },
    methods: {
    },
}

</script>

<style>
@import 'datatables.net-bs5';
@import 'datatables.net-fixedcolumns-dt';
@import 'datatables.net-buttons-bs5';
</style>