/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// import './bootstrap';
import { createApp } from 'vue';
import Antd from 'ant-design-vue';
import Toast from "vue-toastification";

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import VDataTableTahapan from './components/tahapan/VDataTableTahapan.vue';
import VDataTableTahapanDetail from './components/tahapan/VDataTableTahapanDetail.vue';
import VDataTablePengguna from './components/pengguna/VDataTablePengguna.vue';
import VDataTableGroup from './components/group/VDataTableGroup.vue';
import VDataTableSkpd from './components/skpd/VDataTableSkpd.vue';
import VDataTableAkun from './components/akun/VDataTableAkun.vue';
import VDataTableAkunNilai from './components/akun/nilai/VDataTableAkunNilai.vue';
import VDataTableAkunSkpd from './components/akun/nilai/VDataTableAkunSkpd.vue';
import VDataTableAkunSubKeg from './components/akun/nilai/VDataTableAkunSubKeg.vue';
import VDataTableSipd from './components/sipd/VDataTableSipd.vue';
import VDataTableBandingkan from './components/sipd/bandingkan/VDataTableBandingkan.vue';
import VDataTablePrioritas from './components/prioritas/VDataTablePrioritas.vue';
import VDataTableReportPrioritas from './components/prioritas/VDataTableReportPrioritas.vue';
import VDataTableRecapPrioritas from './components/prioritas/VDataTableRecapPrioritas.vue';
import VDataTablePrioritasSk from './components/prioritas-sk/VDataTablePrioritasSk.vue';
import VDataTableReportPrioritasSk from './components/prioritas-sk/VDataTableReportPrioritasSk.vue';
import VDataTableRecapPrioritasSk from './components/prioritas-sk/VDataTableRecapPrioritasSk.vue';
import VDataTablePrioritasSd from './components/prioritas-sd/VDataTablePrioritasSd.vue';
import VDataTableReportPrioritasSd from './components/prioritas-sd/VDataTableReportPrioritasSd.vue';
import VDataTableRecapPrioritasSd from './components/prioritas-sd/VDataTableRecapPrioritasSd.vue';
import VDataTablePrioritasSdSk from './components/prioritas-sd-sk/VDataTablePrioritasSdSk.vue';
import VDataTableReportPrioritasSdSk from './components/prioritas-sd-sk/VDataTableReportPrioritasSdSk.vue';
import VDataTableRecapPrioritasSdSk from './components/prioritas-sd-sk/VDataTableRecapPrioritasSdSk.vue';
import VDataTableSosmed from './components/sosmed/VDataTableSosmed.vue';
import VDataTableRealisasi from './components/realisasi/VDataTableRealisasi.vue';
import VDataTableRBandingkan from './components/realisasi/bandingkan/VDataTableRBandingkan.vue';

app.component('v-data-table-tahapan', VDataTableTahapan);
app.component('v-data-table-tahapan-detail', VDataTableTahapanDetail);
app.component('v-data-table-pengguna', VDataTablePengguna);
app.component('v-data-table-group', VDataTableGroup);
app.component('v-data-table-skpd', VDataTableSkpd);
app.component('v-data-table-akun', VDataTableAkun);
app.component('v-data-table-akun-nilai', VDataTableAkunNilai);
app.component('v-data-table-akun-skpd', VDataTableAkunSkpd);
app.component('v-data-table-akun-subkeg', VDataTableAkunSubKeg);
app.component('v-data-table-sipd', VDataTableSipd);
app.component('v-data-table-bandingkan', VDataTableBandingkan);
app.component('v-data-table-prioritas', VDataTablePrioritas);
app.component('v-data-table-report-prioritas', VDataTableReportPrioritas);
app.component('v-data-table-recap-prioritas', VDataTableRecapPrioritas);
app.component('v-data-table-prioritas-sk', VDataTablePrioritasSk);
app.component('v-data-table-report-prioritas-sk', VDataTableReportPrioritasSk);
app.component('v-data-table-recap-prioritas-sk', VDataTableRecapPrioritasSk);
app.component('v-data-table-prioritas-sd', VDataTablePrioritasSd);
app.component('v-data-table-report-prioritas-sd', VDataTableReportPrioritasSd);
app.component('v-data-table-recap-prioritas-sd', VDataTableRecapPrioritasSd);
app.component('v-data-table-prioritas-sd-sk', VDataTablePrioritasSdSk);
app.component('v-data-table-report-prioritas-sd-sk', VDataTableReportPrioritasSdSk);
app.component('v-data-table-recap-prioritas-sd-sk', VDataTableRecapPrioritasSdSk);
app.component('v-data-table-sosmed', VDataTableSosmed);
app.component('v-data-table-realisasi', VDataTableRealisasi);
app.component('v-data-table-rbandingkan', VDataTableRBandingkan);


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app
.use(Antd)
.use(Toast)
.mixin({ methods: { route: route } })
.mount('#app');
