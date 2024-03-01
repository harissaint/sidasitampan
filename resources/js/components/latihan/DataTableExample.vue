<template>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add Row
        </button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <!-- Add more columns here -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows" :key="row.id">
                    <td>{{ row.id }}</td>
                    <td>{{ row.name }}</td>
                    <!-- Render other row data here -->
                </tr>
            </tbody>
        </table>
        <Modal>
            <Form @addRow="addRow">
                <slot></slot>
            </Form>
        </Modal>
    </div>
</template>
  
  <!-- Rest of the code remains the same -->
<script>
import Modal from './Modal.vue';
import Form from './Form.vue';
import FormAdd from './pages/tahapan/FormAdd.vue';

export default {
    components: {
        Modal,
        Form,
        FormAdd,
    },
    data() {
        return {
            rows: [
                { id: 1, name: 'Row 1' },
                { id: 2, name: 'Row 2' },
                // Add more initial rows here
            ],
        };
    },
    methods: {
        addRow(row) {
            // Add the new row to the rows array
            this.rows.push(row);
        },
    },
};
</script>  