# Refaktor View Blade: Struktur Komponen

Semua elemen view yang semula bercampur dalam halaman besar telah dipisahkan ke `resources/views/components/` berdasarkan tanggung jawabnya. Halaman dalam `views/*-section/` sekarang hanya menjadi **page composition**: menyiapkan data dan memanggil komponen.

## Struktur kategori

```text
views/
├── components/
│   ├── layouts/          # Dokumen HTML, page shell, sidebar + header wrapper
│   ├── ui/               # Elemen visual reusable: header, sidebar, tabs, button, title bar, tile
│   ├── forms/            # Field label, input, select legacy-style
│   ├── tables/           # Pembungkus tabel reusable
│   ├── dashboard/        # Dashboard menu FB & Shop Cashier
│   ├── restaurant/       # Bagian halaman transaksi restoran
│   ├── daily-cashier/    # Filter, report, modal printer, client script ringkasan kasir
│   ├── reports/          # Tampilan laporan/print preview
│   └── modals/           # Semua modal aplikasi
├── restaurant-transaction-section/ # Halaman komposisi
├── daily-cashier-section/          # Halaman komposisi
└── welcome.blade.php
```

## Komponen utama dan fungsinya

| Kategori | Komponen | Fungsi |
|---|---|---|
| Layout | `layouts/app` | Struktur HTML utama, Vite, Lucide, global dialog trigger. |
| Layout | `layouts/legacy-page` | Shell halaman: sidebar, header, dan slot konten. |
| UI | `ui/app-sidebar`, `ui/app-header` | Navigasi vertikal dan header sistem. |
| UI | `ui/breadcrumb-tabs` | Breadcrumb/tab aplikasi kasir. |
| UI | `ui/menu-tile`, `ui/menu-tile-section` | Tile menu pada dashboard. |
| UI | `ui/window-titlebar` | Header standar untuk modal. |
| UI | `ui/legacy-button`, `ui/legacy-style-kit` | Tombol dan style reusable gaya aplikasi lama. |
| Forms | `forms/legacy-field`, `legacy-input`, `legacy-select` | Form field dasar. |
| Tables | `tables/legacy-table-shell` | Container tabel scrollable. |
| Dashboard | `dashboard/cashier-menu-grid` | Grid Master, Transaction, Batch, dan Report. |
| Restaurant | `restaurant/transaction-toolbar` | Toolbar transaksi restoran. |
| Restaurant | `restaurant/table-zone-selector` | Pilihan split zone/table A–J. |
| Restaurant | `restaurant/order-information-form` | Form order + tab order. |
| Restaurant | `restaurant/order-items-panel` | Table item pesanan dan total. |
| Restaurant | `restaurant/service-browser` | Kategori, pencarian, dan menu service. |
| Restaurant | `restaurant/table-list-panel` | Halaman Table List. |
| Restaurant | `restaurant/waiter-modal` | Pemilih waiter. |
| Restaurant | `restaurant/transaction-client` | JavaScript interaksi transaksi. |
| Daily Cashier | `daily-cashier/summary-filter` | Filter laporan kasir. |
| Daily Cashier | `daily-cashier/summary-report` | Output dan tabel ringkasan kasir. |
| Daily Cashier | `daily-cashier/printer-properties-modal` | Pengaturan printer. |
| Daily Cashier | `daily-cashier/summary-client` | JavaScript laporan ringkasan kasir. |
| Reports | `reports/daily-cashier-summary-print` | Preview/print report. |
| Modals | `modals/*` | Login, cashier lookup, folio lookup, reservation, split, billing, settle. |
| Modals | `modals/settlement/*` | Child modal Card Lookup, Payment List, Confirmation, dan Printing. |

## Perubahan nama view modal

Gunakan tag Blade berikut pada halaman yang memerlukan modal:

```blade
<x-modals.cashier-login-modal id="cashierLoginModal" />
<x-modals.cashier-lookup-modal id="cashierLookupModal" />
<x-modals.folio-lookup-modal id="folioLookupModal" />
<x-modals.reservation-folio-lookup-modal id="reservationFolioLookupModal" />
<x-modals.move-split-modal id="moveSplitModal" />
<x-modals.print-billing-modal id="printBillingModal" />
<x-modals.settle-modal id="settleModal" :total-amount="20000" />
```

Untuk membuka dialog dari tombol, cukup gunakan atribut berikut. Handler global berada di `layouts/app.blade.php`.

```blade
<button type="button" data-open-dialog="settleModal">Settle</button>
```

## Halaman yang sudah disesuaikan

- `restaurant-transaction-section/menu-section.blade.php`
- `restaurant-transaction-section/table-list.blade.php`
- `restaurant-transaction-section/transaction.blade.php`
- `daily-cashier-section/daily-cashier-summary.blade.php`
- `daily-cashier-section/daily-cashier-summary-print.blade.php`
- `welcome.blade.php`

## Catatan integrasi backend

Komponen saat ini masih memakai data dummy/default seperti source awal. Saat controller sudah siap, cukup ganti array di page view atau kirim props dari controller ke view. Struktur komponen tidak perlu diubah.
