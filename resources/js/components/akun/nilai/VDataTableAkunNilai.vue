<template>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6>List Data Master Nilai Akun Rekening</h6>
    </div>

    <div class="mb-3">
        <form class="row g-2">
            <div class="col-md-12 form-group">
                <label for="tahapan_id" class="form-label">Tahapan <span class="text-danger">*</span></label>
                <select class="form-select" id="tahapan_id" v-model="tahapan_id" @change="fetchData" required>
                    <option v-for="tahap in tahapans" :key="tahap.id" :value="tahap.id" :class="tahap.tahun">
                        {{ tahap.nama }}
                    </option>
                </select>
            </div>

            <button type="button" class="btn btn-primary" @click="submitData">
                <i class="bx bx-search me-0"></i>
                Cari Nilai
            </button>
        </form>
    </div>

    <!-- <div class="table-responsive"> -->
    <DataTable id="dt-table" :columns="columns" :options="options" class="table table-bordered" v-if="!isLoading" />
    <!-- </div> -->
</template>

<script>
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import 'datatables.net-fixedheader-dt';
import { useToast } from "vue-toastification";
import axios from 'axios';
import $ from 'jquery'; // Import jQuery if you haven't already

import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';

DataTable.use(DataTablesCore);

let rows = [];

const columns = [{
    data: 'id',
    title: 'No',
    render: function (data, type, row, meta) {
        return meta.row + 1;
    },
    orderable: false,
    searchable: false,
}, {
    data: 'kode',
    title: 'Kode',
}, {
    data: 'nama',
    title: 'Nama',
}, {
    data: 'nilai',
    title: 'Nilai',
    render: function (data, type, row, meta) {
        return Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(data);
    },
}, {
    data: 'kode',
    title: 'Aksi',
    render: function (data, type, row, meta) {
        return `
            <a href="${route('master.akun.nilai.skpd', data)}?tahapan_id=${row.tahapan_id}" class="btn btn-info btn-sm">SKPD</a>
        `;
    },
    orderable: false,
    searchable: false,
}];

export default {
    components: {
        DataTable,
    },
    data() {
        return {
            tahapans: [],
            isLoading: true,
            toast: useToast(),
            rows: rows,
            columns: columns,
            options: {
                search :{
                    regex: true
                },
                orderCellsTop: true,
                fixedHeader: true,
                processing: false,
                serverSide: false,
                ajax: route('master.akun.nilai'),
                initComplete: function () {
                    var api = this.api();

                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            // Set the header cell to contain the input element
                            var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            var title = $(cell).text();
                            $(cell).html('<input type="text" placeholder="' + title + '" />');

                            // On every keypress in this input
                            $('input', $('.filters th').eq($(api.column(colIdx).header()).index()))
                                .off('keyup change')
                                .on('change', function (e) {
                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();
                                })
                                .on('keyup', function (e) {
                                    e.stopPropagation();

                                    $(this).trigger('change');
                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                },
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
            this.options.ajax = {
                url: route('master.akun.nilai'),
                type: 'GET',
                data: {
                    // tahun: this.tahun,
                    tahapan_id: this.tahapan_id
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
        },
    }
}
</script>

<style>
@import 'datatables.net-fixedheader-dt';
@import 'datatables.net-bs5';
@import 'datatables.net-fixedcolumns-dt';
@import 'datatables.net-buttons-bs5';

thead input {
    width: 100%;
}
</style>