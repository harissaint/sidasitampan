<template>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6>List Data Master Tahapan</h6>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bx bx-plus me-0"></i>
            </button>
        </div>
    </div>

    <ModalAdd>
        <FormAdd @addData="addData"></FormAdd>
    </ModalAdd>

    <!-- <div class="table-responsive"> -->
    <DataTable id="dt-table" :columns="columns" :options="options" class="table table-bordered" />
    <!-- </div> -->
</template>

<script>
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import { useToast } from "vue-toastification";
import axios from 'axios';
import $ from 'jquery'; // Import jQuery if you haven't already

import ModalAdd from './ModalAddTahapan.vue';
import FormAdd from './FormAddTahapan.vue';

DataTable.use(DataTablesCore);

let rows = [];

const columns = [{
    data: 'id',
    title: 'No',
    render: function (data, type, row, meta) {
        return meta.row + 1;
    },
}, {
    data: 'nama',
    title: 'Nama',
}, {
    data: 'tahun',
    title: 'Tahun',
}, {
    data: 'keterangan',
    title: 'Keterangan',
}, {
    data: 'total_pendapatan',
    title: 'Total Pendapatan',
    render: function (data, type, row, meta) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
    }
}, {
    data: 'total_belanja',
    title: 'Total Belanja',
    render: function (data, type, row, meta) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
    }
}, {
    data: 'total_pembiayaan',
    title: 'Total Pembiayaan',
    render: function (data, type, row, meta) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
    }
}, {
    data: 'is_active',
    title: 'Status',
    render: function (data, type, row, meta) {
        if (data == 1) {
            return `<span class="badge bg-success">Aktif</span>`;
        }
        else {
            return `<span class="badge bg-secondary">Tidak Aktif</span>`;
        }
    }
}, {
    data: 'id',
    title: 'Aksi',
    render: function (data, type, row, meta) {
        // rows.push(row);

        let actionHtml = `
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal${row.id}">
                <i class="bx bx-edit-alt me-0"></i>
            </button>

            <div class="modal fade" id="editModal${row.id}" tabindex="-1" aria-labelledby="editModal${row.id}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg">
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
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="${row.nama}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="tahun" name="tahun" value="${row.tahun}" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="inputDescription" name="keterangan" rows="3" required>${row.keterangan}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="is_active" name="is_active" required>
                                        <option value="1" ${row.is_active == 1 ? 'selected' : ''}>Aktif</option>
                                        <option value="0" ${row.is_active == 0 ? 'selected' : ''}>Tidak Aktif</option>
                                    </select>
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
                                <p>Apakah anda yakin ingin menghapus tahapan: <strong>${row.nama}</strong>?</p>
                                <!-- Add more form fields here -->
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tidak</button>
                                    <button type="submit" class="btn btn-primary btn-delete">Iya</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            `;

        let uploadSipd = `
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal${row.id}">
                <i class="bx bx-upload me-0"></i>
            </button>

            <div class="modal fade" id="uploadModal${row.id}" tabindex="-1" aria-labelledby="uploadModal${row.id}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content  border-top border-0 border-4 border-primary">
                        <div class="modal-header">
                            <div class="d-flex align-items-center">
                                <div><i class="bx bx-file me-1 font-22"></i>
                                </div>
                                <h5 class="mb-0">Import Data SIPD</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" onsubmit="return false;" enctype="multipart/form-data">
                                <input type="hidden" name="tahapan_id" value="${row.id}">
                                <div class="col-md-6">
                                    <label for="jenis" class="form-label">Jenis <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis" id="jenis1" value="pendapatan/pembiayaan">
                                        <label class="form-check-label" for="jenis1">
                                            Pendapatan/Pembiayaan
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis" id="jenis2" value="belanja">
                                        <label class="form-check-label" for="jenis2">
                                            Belanja
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="format" class="form-label">Format <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="format2" value="v2023">
                                        <label class="form-check-label" for="format2">
                                            Versi 2023
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="format2" value="v2024">
                                        <label class="form-check-label" for="format2">
                                            Versi 2024
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="file" class="form-label">File <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                </div>
                                <!-- Add more form fields here -->
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary btn-upload">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            `;

        let detailBtn = `
            <a href="${route('master.tahapan.show', row.id)}" class="btn btn-info btn-sm">
                <i class="bx bx-info-circle me-0"></i>
            </a>`;

        return actionHtml + uploadSipd + detailBtn;
    },
    orderable: false,
    searchable: false,
}];

export default {
    components: {
        DataTable,
        ModalAdd,
        FormAdd,
    },
    data() {
        return {
            toast: useToast(),
            rows: rows,
            columns: columns,
            options: {
                processing: true,
                serverSide: true,
                ajax: route('master.tahapan.index'),
            }
        }
    },
    // setup() {
    //     // Get toast interface
    //     console.log('setup')
    // },
    mounted() {
        const el = this;
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

        // add event listener
        $('#dt-table').on('click', '.btn-upload', function () {
            // get form data
            let form = $(this).closest('form')[0];
            const formData = new FormData(form);
            let btn = $(this);
            // delete data
            el.uploadData(formData, btn);
        });
    },
    methods: {
        uploadData(formData, btn) {
            // console.log(formData);
            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }
            btn.attr('disabled', true)
            btn.html(`
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            `)

            // store data
            axios.post(route('master.sipd.store'), formData, config)
                .then(response => {
                    // console.log(response.data);
                    // close modal
                    $('#uploadModal' + formData.get('tahapan_id')).trigger('click');
                    // reload datatable
                    $('#dt-table').DataTable().ajax.reload();
                    this.toast.success("Data berhasil ditambahkan");
                })
                .catch(error => {
                    // console.log(error);
                    if (error.response.status == 422) {
                        this.toast.error(error.response.data.message);
                    }
                    else {
                        this.toast.error("Data gagal ditambahkan");
                    }
                })
                .finally(() => {
                    btn.attr('disabled', false)
                    btn.html(`
                        Upload
                    `)
                });
        },
        addData(formData) {
            // store data
            axios.post(route('master.tahapan.store'), formData)
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
        updateData(formData) {
            // validation
            let val = true;
            let el = this;
            formData.forEach(function (data) {
                console.log(data);
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
                nama: formData[1].value,
                tahun: formData[2].value,
                keterangan: formData[3].value,
                is_active: formData[4].value
            };
            // update data
            axios.put(route('master.tahapan.update', formData.id), formData)
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
            axios.delete(route('master.tahapan.destroy', formData[0].value))
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