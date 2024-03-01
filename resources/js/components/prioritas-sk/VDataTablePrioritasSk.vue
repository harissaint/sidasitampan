<template>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6>List Data Prioritas</h6>
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

import ModalAdd from './ModalAddPrioritasSk.vue';
import FormAdd from './FormAddPrioritasSk.vue';

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
},  {
    data: 'tahun',
    title: 'Tahun',
}, {
    data: 'keterangan',
    title: 'Keterangan',
}, {
    data:'sk_maps',
    title: 'Jumlah Maping',
    render: function (data, type, row, meta) {
        return row.sk_maps.length + ' Sub Kegiatan';
    },
},  {
    data: 'id',
    title: 'Aksi',
    render: function (data, type, row, meta) {
        let deleteP = '';
        if(row.sk_maps.length > 0){
            deleteP= `Apakah anda yakin ingin menghapus map prioritas: <strong>${row.nama}`;
        }else{
            deleteP= `Apakah anda yakin ingin menghapus prioritas: <strong>${row.nama}`;
        }
        return `
            <a href="${route('master.prioritas-sk.show', row.id)}" class="btn btn-info btn-sm">
                <i class="bx bx-info-circle me-0"></i>
            </a>

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
                                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="${row.nama}" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tahun" name="tahun" value="${row.tahun}" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="keterangan" class="form-label">Nama <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" required>${row.keterangan}</textarea>
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
                                <p>${deleteP}?</p>
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

            <a href="${route('master.prioritas-sk.report', row.id)}" class="btn btn-secondary btn-sm">
                <i class="bx bx-file"></i> Report Detail
            </a>

            <a href="${route('master.prioritas-sk.recap', row.id)}" class="btn btn-secondary btn-sm">
                <i class="bx bx-file"></i> Report Recap
            </a>`;
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
                ajax: route('master.prioritas-sk.index'),
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
    },
    methods: {
        addData(formData) {
            // store data
            axios.post(route('master.prioritas-sk.store'), formData)
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
                nama: formData[1].value,
            };
            // update data
            axios.put(route('master.prioritas-sk.update', formData.id), formData)
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
            axios.delete(route('master.prioritas-sk.destroy', formData[0].value))
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