<template>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6>List Data Pengguna</h6>
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

import ModalAdd from './ModalAddPengguna.vue';
import FormAdd from './FormAddPengguna.vue';

DataTable.use(DataTablesCore);

let rows = [];

const columns = [{
    data: 'id',
    title: 'No',
    render: function (data, type, row, meta) {
        return meta.row + 1;
    },
}, {
    data: 'name',
    title: 'Nama',
}, {
    data: 'email',
    title: 'Email',
}, {
    data: 'group',
    title: 'Group',
    render: function (data, type, row, meta) {
        if(row.group == null)
        {
            return '-';
        }
        else{

            if (row.group.nama == 'SKPD') {
                return row.group.nama + ' (' + row.skpd.nama + ')';
            }
            return row.group.nama;
        }

    },
}, {
    data: 'status',
    title: 'Status',
    render: function (data, type, row, meta) {
        return row.status == 1 ? 'Aktif' : 'Tidak Aktif';
    },
}, {
    data: 'id',
    title: 'Aksi',
    render: function (data, type, row, meta) {

        return `
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
                                <div class="col-md-12">
                                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="${row.name}" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="${row.email}" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="col-md-12">
                                    <label for="role" class="form-label">Group <span class="text-danger">*</span></label>
                                    <select class="form-select" id="role" name="role" data-group="${row.group_id}" required>
                                        
                                    </select>
                                </div>
                                <div class="col-md-12 ${row.group?.nama == 'SKPD' ? '' : 'd-none'}">
                                    <label for="skpd" class="form-label">SKPD <span class="text-danger">*</span></label>
                                    <select class="form-select" id="skpd" name="skpd" data-skpd="${row.skpd_id}">
                                        
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1" ${row.status == 1 ? 'selected' : ''}>Aktif</option>
                                        <option value="0" ${row.status == 0 ? 'selected' : ''}>Tidak Aktif</option>
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
                                <p>Apakah anda yakin ingin menghapus pengguna: <strong>${row.name}</strong>?</p>
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
                ajax: route('setting.pengguna.index'),
            },
            groups: []
        }
    },
    // setup() {
    //     // Get toast interface
    //     console.log('setup')
    // },
    mounted() {
        const el = this;
        this.fetchData();
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
        $('#dt-table').on('change', 'select[name="role"]', function () {
            // get val
            let val = $(this).val();
            if (val.split('_')[1] == 'SKPD') {
                // this parent remove d-none
                $(this).parent().next().removeClass('d-none');
            } else {
                // this parent remove d-none
                $(this).parent().next().addClass('d-none');
                $(this).parent().next().find('select[name="skpd"]').val('');
            }
        });
    },
    methods: {
        addData(formData) {
            // store data
            axios.post(route('setting.pengguna.store'), formData)
                .then(response => {
                    // console.log(response.data)
                    // reload datatable
                    $('#dt-table').DataTable().ajax.reload();
                    //
                    this.fetchData();
                    this.toast.success("Data berhasil ditambahkan");
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        let obj = error.response.data.errors
                        Object.keys(obj).forEach(key => {
                            this.toast.error(obj[key][0]);
                        })
                    } else {
                        this.toast.error("Data gagal ditambahkan");
                    }
                });
        },
        updateData(formData) {
            // validation
            let val = true;
            let el = this;
            formData.forEach(function (data) {
                // console.log(data);
                if (data.value == '' && data.name != 'password') {
                    el.toast.error(`Data ${data.name} tidak boleh kosong`);
                    val = false;
                    // stop loop
                    return false;
                }
            });
            // stop function
            if (!val) return false;
            // new form data
            let group = formData[4].value.split('_')[1]
            let skpd_id = group == 'SKPD' ? formData[5].value : '';
            formData = {
                id: formData[0].value,
                name: formData[1].value,
                email: formData[2].value,
                password: formData[3].value,
                group_id: formData[4].value.split('_')[0],
                skpd_id: skpd_id,
                status: formData[6].value,
            };
            // update data
            axios.put(route('setting.pengguna.update', formData.id), formData)
                .then(response => {
                    // close modal
                    $('#editModal' + formData.id).trigger('click');
                    // reload datatable                    
                    $('#dt-table').DataTable().ajax.reload();
                    this.fetchData();
                    this.toast.success("Data berhasil diubah");
                })
                .catch(error => {
                    console.log(error);
                    this.toast.error("Data gagal diubah");
                });
        },
        deleteData(formData) {
            // delete data
            axios.delete(route('setting.pengguna.destroy', formData[0].value))
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
        fetchData() {
            axios.get(route('api.master.group.index'))
                .then(response => {
                    // console.log(response.data.data);
                    this.groups = response.data.data;
                    // add option to select name = role
                    let options = '';
                    this.groups.forEach(item => {
                        options += `<option value="${item.id}_${item.nama}">${item.nama}</option>`;
                    });
                    $('select[name="role"]').html(options);
                    $('select[name="role"]').each(function () {
                        let group_id = $(this).attr("data-group");
                        // foreach option from this
                        $(this).children('option').each(function () {
                            let val = $(this).val().split("_")[0];
                            if (val == group_id) {
                                $(this).attr('selected', true);
                            }
                        });
                    });
                })
                .catch(error => {
                    console.log(error);
                });

            axios.get(route('api.master.skpd.index'))
                .then(response => {
                    // console.log(response.data.data);
                    // console.log(response.data.data);
                    this.groups = response.data.data;
                    // add option to select name = role
                    let options = '';
                    this.groups.forEach(item => {
                        options += `<option value="${item.id}">${item.nama}</option>`;
                    });
                    $('select[name="skpd"]').html(options);
                    $('select[name="skpd"]').each(function () {
                        let skpd_id = $(this).attr("data-skpd");
                        // foreach option from this
                        $(this).children('option').each(function () {
                            let val = $(this).val()
                            if (val == skpd_id) {
                                $(this).attr('selected', true);
                            }
                        });
                    });
                })
                .catch(error => {
                    console.log(error);
                });
        },
    },
}

</script>