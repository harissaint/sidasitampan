<template>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Add Row
    </button>

    <Modal>
        <Form @addRow="addRow"></Form>
    </Modal>

    <DataTable id="example" :columns="cols" :data="rows" class="table table-bordered" />
</template>

<script>
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import Modal from './Modal.vue';
import Form from './Form.vue';
import FormAdd from './pages/tahapan/FormAdd.vue';

DataTable.use(DataTablesCore);

const data = [{
    id: 1,
    nama: 'Row 1',
    tahun: '2021',
    keterangan: 'Keterangan 1',
}, {
    id: 2,
    nama: 'Row 2',
    tahun: '2021',
    keterangan: 'Keterangan 2',
}];

const columns = [{
    data: 'id',
    title: 'ID',
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
    data: 'id',
    title: 'Action',
    render: function (data, type, row, meta) {
        return `
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Edit
            </button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Delete
            </button>
        `;
    },
}];

const options = {
    processing: true,
    serverSide: true,
}

export default {
    components: {
        DataTable,
        Modal,
        FormAdd,
        Form,
    },
    data() {
        return {
            rows: data,
            cols: columns,
        };
    },
    methods: {
        addRow(newRow) {
            // Add the new row to the rows array
            // console.log(newRow);
            this.rows.push(newRow);
        },
    }
}
</script>

<style>
@import 'datatables.net-bs5';
</style>