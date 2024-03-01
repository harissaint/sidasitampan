<template>
    <div class="mb-3">
        <h6>Report Recap Data Master Prioritas</h6>
    </div>

    <!-- <div class="table-responsive"> -->
    <DataTable id="dt-table" :columns="columns" :options="options" class="table table-bordered table-hover"
        style="width:100%">
        <tfoot>
            <tr>
                <th colspan="2" style="text-align:right">Total:</th>
                <th></th>
            </tr>
        </tfoot>
    </DataTable>
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
let total = 0;
let pageTotal = 0;

const columns = [{
    data: 'kode_skpd',
    title: 'No',
    render: function (data, type, row, meta) {
        return meta.row + 1;
    },
}, {
    data: 'nama_skpd',
    title: 'SKPD',
    render: function (data, type, row, meta) {
        return row.kode_skpd + ' - ' + row.nama_skpd;
    },
}, {
    data: 'nilai_rincian',
    title: 'Anggaran',
    render: function (data, type, row, meta) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
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
                footerCallback: function (row, data, start, end, display) {
                    // add footer
                    var api = this.api(),
                        data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(2, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(2).footer()).html(
                        new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(pageTotal) + ' dari ( ' + new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(total) + ' total)'
                    );
                },
                fixedColumns: {
                    left: 0,
                    right: 1
                },
                processing: false,
                serverSide: false,
                ajax: {
                    url: route('master.prioritas-sd-sk.recap', this.prioritas_id),
                    type: 'GET',
                    data: {
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
        this.fetchData();
    },
    methods: {
        fetchData() {
            axios.get(route('api.master.tahapan.index'))
                .then(response => {
                    // console.log(response.data.data);
                    this.tahapans = response.data.data;
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
    },
}

</script>

<style>
@import 'datatables.net-bs5';
@import 'datatables.net-fixedcolumns-dt';
@import 'datatables.net-buttons-bs5';
</style>