<template>
    <div class="mb-3">
        <h6>List Data Master Nilai Akun Rekening (Sub Kegiatan {{ skpd_nama }})</h6>

        <p>Kode Rekening : {{ akun_kode }}</p>
        <p>Nama Rekening : {{ akun_nama }}</p>
    </div>

    <!-- <div class="table-responsive"> -->
    <DataTable id="dt-table" :columns="columns" :options="options" class="table table-bordered" />
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
    data: 'kode_sub_kegiatan',
    title: 'Kode Sub Kegiatan',
}, {
    data: 'nama_sub_kegiatan',
    title: 'Nama Sub Kegiatan',
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
}];

export default {
    props: {
        akun_kode: {
            required: true,
        },
        akun_nama: {
            required: true,
        },
        skpd_kode: {
            required: true,
        },
        skpd_nama: {
            required: true,
        },
        tahapan_id: {
            required: true,
        },
    },
    components: {
        DataTable,
    },
    data() {
        return {
            tahapans: [],
            toast: useToast(),
            rows: rows,
            columns: columns,
            options: {
                search :{
                    regex: true
                },
                orderCellsTop: true,
                fixedHeader: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: route('master.akun.nilai.subkeg', [this.akun_kode, this.skpd_kode]),
                    data: {
                        tahapan_id: this.tahapan_id,
                    },
                    beforeSend: function (request) {
                        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                },
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