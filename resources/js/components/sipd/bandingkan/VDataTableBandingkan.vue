<template>
    <div class="mb-3">
        <h6>Bandingkan Data Master SIPD</h6>

        <form class="row g-2">
            <div class="col-md-12 form-group" v-if="group!='SKPD'">
                <label for="skpd" class="form-label">SKPD <span class="text-danger">*</span></label>
                <select class="form-select" id="skpd" v-model="skpd" @change="fetchData" required>
                    <option value="">Semua SKPD</option>
                    <option v-for="skpd in skpds" :key="skpd.id" :value="skpd.kode">{{ skpd.nama }}</option>
                </select>
            </div>
            <div class="col-md-12 form-group" v-else>
                <label for="skpd" class="form-label">SKPD</label>
                <h5>{{ skpd_nama }}</h5>
            </div>
            <div class="col-md-6 form-group">
                <label for="tahap_id_1" class="form-label">Tahap 1 <span class="text-danger">*</span></label>
                <select class="form-select" id="tahap_id_1" v-model="tahap_id_1" @change="fetchData" required>
                    <option v-for="tahap in tahapans" :key="tahap.id" :value="tahap.id">{{ tahap.nama }}</option>
                </select>
            </div>
            <div class="col-md-6 form-group">
                <label for="tahap_id_2" class="form-label">Tahap 2 <span class="text-danger">*</span></label>
                <select class="form-select" id="tahap_id_2" v-model="tahap_id_2" @change="fetchData" required>
                    <option v-for="tahap in tahapans" :key="tahap.id" :value="tahap.id">{{ tahap.nama }}</option>
                </select>
            </div>
            <!-- <div class="col-md-2 form-group"> -->
            <!-- <div class="d-flex justify-content-end align-items-center w-100"> -->
            <button type="button" class="btn btn-primary" @click="submitData">
                <i class="bx bx-search me-0"></i>
                Bandingkan
            </button>
            <!-- </div> -->
            <!-- </div> -->
        </form>
    </div>

    <!-- <div class="table-responsive"> -->
    <DataTable id="dt-table" :columns="columns" :options="options" class="table table-bordered table-hover"
        style="width:100%" v-if="!isLoading" />
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
    data: 'skpd',
    title: 'No',
    render: function (data, type, row, meta) {
        return meta.row + 1;
    },
}, {
    data: 'urusan',
    title: 'Urusan',
}, {
    data: 'bidang_urusan',
    title: 'Bidang Urusan',
}, {
    data: 'skpd',
    title: 'SKPD',
}, {
    data: 'program',
    title: 'Program',
}, {
    data: 'kegiatan',
    title: 'Kegiatan',
}, {
    data: 'sub_kegiatan',
    title: 'Sub Kegiatan',
}, {
    data: 'akun',
    title: 'Rekening',
}, {
    data: 'anggaran1',
    title: 'Anggaran Tahap 1',
    render: function (data, type, row, meta) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
    },
    className: 'text-end',
}, {
    data: 'anggaran2',
    title: 'Anggaran Tahap 2',
    render: function (data, type, row, meta) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
    },
    className: 'text-end',
}, {
    data: 'selisih',
    title: 'Selisih',
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
    },
    components: {
        DataTable
    },
    data() {
        return {
            skpds: [],
            tahapans: [],
            tahap_id_1: '',
            tahap_id_2: '',
            skpd: '',
            isLoading: true,
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
                ajax: route('master.sipd.bandingkan'),
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
            // set options ajax
            console.log(this.tahap_id_1);
            this.options.ajax = {
                url: route('master.sipd.bandingkan'),
                type: 'GET',
                data: {
                    tahap_id_1: this.tahap_id_1,
                    tahap_id_2: this.tahap_id_2,
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