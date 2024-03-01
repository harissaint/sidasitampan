<template>
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content  border-top border-0 border-4 border-primary">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div><i class="bx bx-file me-1 font-22"></i>
                        </div>
                        <h5 class="mb-0">Import File Akun Rekening</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
    },
    data() {
        return {
            modal: null,
        };
    },
    mounted() {
        this.modal = new bootstrap.Modal(document.getElementById('importModal'));
    },
    methods: {
        open() {
            this.modal.show();
        },
        close() {
            console.log('close')
            this.modal.hide();
        },
    },
};
</script>
  