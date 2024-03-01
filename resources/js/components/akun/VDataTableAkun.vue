<template>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6>List Data Master Akun Rekening</h6>
        <div>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bx bx-plus me-0"></i>
            </button>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bx bx-import me-0"></i>
            </button>
        </div>
    </div>

    <ModalAdd>
        <FormAdd @addData="addData"></FormAdd>
    </ModalAdd>

    <ModalImport>
        <FormImport @importData="importData"></FormImport>
    </ModalImport>

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

import ModalAdd from './ModalAddAkun.vue';
import FormAdd from './FormAddAkun.vue';
import ModalImport from './ModalImportAkun.vue';
import FormImport from './FormImportAkun.vue';

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
    data: 'tahun',
    title: 'Tahun',
}, {
    data: 'level',
    title: 'Level',
}, {
    data: 'id',
    title: 'Aksi',
    render: function (data, type, row, meta) {
        rows.push(row);
        return `
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal${row.id}">
                <i class="bx bx-edit-alt me-0"></i>
            </button>

            <div class="modal fade" id="editModal${row.id}" tabindex="-1" aria-labelledby="editModal${row.id}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content  border-top border-0 border-4 border-warning">
                        <div class="modal-header">
                            <div class="d-flex align-items-center">
                                <div><i class="bx bx-file me-1 font-22"></i>
                                </div>
                                <h5 class="mb-0">Formulir Perubahan Data</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" onsubmit="return false;">
                                <input type="hidden" name="id" value="${row.id}">
                                <div class="col-md-12">
                                    <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kode" name="kode" value="${row.kode}" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="${row.nama}" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="tahun" name="tahun" value="${row.tahun}" required>
                                </div>
                                <!-- Add more form fields here -->
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary btn-update">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal${row.id}">
                <i class="bx bx-trash me-0"></i>
            </button>
            <div class="modal fade" id="deleteModal${row.id}" tabindex="-1" aria-labelledby="deleteModal${row.id}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content border-top border-0 border-4 border-danger">
                        <div class="modal-header">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0">Peringatan Penghapusan Data!</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" onsubmit="return false;">
                                <input type="hidden" name="id" value="${row.id}">
                                <p>Apakah anda yakin ingin menghapus Akun Rekening: <strong>${row.nama}</strong>?</p>
                                <!-- Add more form fields here -->
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tidak</button>
                                    <button type="submit" class="btn btn-primary btn-delete">Iya</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>`;
    },
    orderable: false,
    searchable: false,
}];

export default {
    components: {
        DataTable,
        ModalAdd,
        FormAdd,
        ModalImport,
        FormImport,
    },
    data() {
        return {
            toast: useToast(),
            rows: rows,
            columns: columns,
            options: {
                orderCellsTop: true,
                fixedHeader: true,
                processing: true,
                serverSide: true,
                ajax: route('master.akun.index'),
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
                            $('input',$('.filters th').eq($(api.column(colIdx).header()).index()))
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
    setup() {

    },
    mounted() {
        const el = this;
        // $('#dt-table thead tr')
        //     .clone(true)
        //     .addClass('filters')
        //     .appendTo('#dt-table thead');
        // add event listener
        $('#dt-table').on('click', '.btn-update', function () {
            // get form data
            let formData = $(this).closest('form').serializeArray();
            // update data
            el.updateData(formData);
        });

        // add event listener
        $('#dt-table').on('click', '.btn-delete', function () {
            // get form data
            let formData = $(this).closest('form').serializeArray();
            // delete data
            el.deleteData(formData);
        });
    },
    methods: {
        addData(formData) {
            // store data
            axios.post(route('master.akun.store'), formData)
                .then(response => {
                    // console.log(response.data)
                    // reload datatable
                    $('#dt-table').DataTable().ajax.reload();
                    this.toast.success("Data berhasil ditambahkan");
                })
                .catch(error => {
                    console.log(error);
                    this.toast.error("Data gagal ditambahkan");
                });
        },
        importData(formData) {
            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }
            // store data
            axios.post(route('master.akun.import'), formData, config)
                .then(response => {
                    console.log(response.data)
                    // reload datatable
                    $('#dt-table').DataTable().ajax.reload();
                    this.toast.success("Data berhasil diimport");
                })
                .catch(error => {
                    // console.log(error);
                    if(error.response.status == 422)
                    {
                        this.toast.error(error.response.data.message);
                    }
                    else
                    {
                        this.toast.error("Data gagal diimport");
                    }
                });
        },
        updateData(formData) {
            // validation
            let val = true;
            let el = this;
            formData.forEach(function (data) {
                // console.log(data);
                if (data.value == '') {
                    el.toast.error(`Data ${data.name} tidak boleh kosong`);
                    val = false;
                    // stop loop
                    return false;
                }
            });
            // stop function
            if (!val) return false;
            // new form data
            formData = {
                id: formData[0].value,
                kode: formData[1].value,
                nama: formData[2].value,
                tahun: formData[3].value,
            };
            // update data
            axios.put(route('master.akun.update', formData.id), formData)
                .then(response => {
                    // close modal
                    $('#editModal' + formData.id).trigger('click');
                    // reload datatable
                    $('#dt-table').DataTable().ajax.reload();
                    this.toast.success("Data berhasil diubah");
                })
                .catch(error => {
                    console.log(error);
                    this.toast.error("Data gagal diubah");
                });
        },
        deleteData(formData) {
            // delete data
            axios.delete(route('master.akun.destroy', formData[0].value))
                .then(response => {
                    // close modal
                    $('#deleteModal' + formData[0].value).trigger('click');
                    // reload datatable
                    $('#dt-table').DataTable().ajax.reload();
                    this.toast.success("Data berhasil dihapus");
                })
                .catch(error => {
                    console.log(error);
                    this.toast.error("Data gagal dihapus");
                });
        },
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