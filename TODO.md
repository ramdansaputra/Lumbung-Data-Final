# Refactor Bantuan Show + Modal Tambah Peserta TODO

## ✅ Plan Confirmed
- Layout consistency with index.blade.php (dark mode, rounded-xl, table toolbar/pagination/footer)
- New modal-tambah-peserta-bantuan.blade.php (3 tabs, searchable dropdowns, preview)
- Controller data injection + store update

## Steps
- [ ] 1. Update BantuanController.php show(): Add $dataPenduduk/$dataKeluarga/$dataRumahTangga queries
- [ ] 2. Create resources/views/admin/partials/modal-tambah-peserta-bantuan.blade.php
- [ ] 3. Refactor show.blade.php: dark mode, toolbar, manual pagination, modal triggers, include partial
- [ ] 4. Update BantuanPesertaController.php store(): Handle tipe_peserta + peserta_id
- [ ] 5. Test & Complete

