<template>
    <div class="mb-3">
        <h6>Report Recap Data Master Prioritas</h6>

        <form class="row g-2">
            <!-- <div class="col-md-12 form-group" v-if="group!='SKPD'">
                <label for="skpd" class="form-label">SKPD <span class="text-danger">*</span></label>
                <select class="form-select" id="skpd" v-model="skpd" @change="fetchData" required>
                    <option value="">Semua SKPD</option>
                    <option v-for="skpd in skpds" :key="skpd.id" :value="skpd.kode">{{ skpd.nama }}</option>
                </select>
            </div>
            <div class="col-md-12 form-group" v-else>
                <label for="skpd" class="form-label">SKPD</label>
                <h5>{{ skpd_nama }}</h5>
            </div> -->
            <div class="col-md-12 form-group">
                <label for="tahapan_id" class="form-label">Tahapan <span class="text-danger">*</span></label>
                <select class="form-select" id="tahapan_id" v-model="tahapan_id" @change="fetchData" required>
                    <option v-for="tahap in tahapans" :key="tahap.id" :value="tahap.id">{{ tahap.nama }}</option>
                </select>
            </div>
            <!-- <div class="col-md-2 form-group"> -->
            <!-- <div class="d-flex justify-content-end align-items-center w-100"> -->
            <button type="button" class="btn btn-primary" @click="submitData">
                <i class="bx bx-search me-0"></i>
                Generate
            </button>
            <!-- </div> -->
            <!-- </div> -->
        </form>
    </div>

    <!-- <div class="table-responsive"> -->
    <DataTable id="dt-table" :columns="columns" :options="options" class="table table-bordered table-hover"
        style="width:100%" v-if="!isLoading">
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
            isLoading: true,
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
                        }).format(pageTotal) + ' dari ( '+new Intl.NumberFormat('id-ID', {
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
                ajax: route('master.prioritas.recap', this.prioritas_id),
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
        submitData() {
            // cek if tahapan_id is empty
            if (this.tahapan_id == '') {
                this.toast.error('Tahapan harus diisi!');
                return;
            }

            // set options ajax
            this.options.ajax = {
                url: route('master.prioritas.recap', this.prioritas_id),
                type: 'GET',
                data: {
                    tahapan_id: this.tahapan_id,
                    skpd: this.skpd
                },
                beforeSend: function (request) {
                    request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
            };
            this.options.processing = true;
            this.options.serverSide = true;

            this.isLoading = false;

            // check if datatable already initialized
            if ($.fn.DataTable.isDataTable('#dt-table')) {
                // destroy datatable
                $('#dt-table').DataTable().destroy();
                // initialize datatable
                $('#dt-table').DataTable(this.options);
            } else {
                // initialize datatable
                $('#dt-table').DataTable(this.options);
            }
        },
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