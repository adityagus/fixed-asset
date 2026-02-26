# Fixed Asset Management System

Sistem manajemen aset tetap (Fixed Asset) berbasis web yang dibangun dengan Laravel 10 dan Vue 3. Aplikasi ini memungkinkan pengelolaan inventaris aset perusahaan dengan fitur yang lengkap dan interface yang modern.

## ✨ Fitur Utama

- 📦 Manajemen Aset Tetap
- 🔍 QR Code Scanner untuk tracking aset
- 📊 Dashboard dengan visualisasi data (ApexCharts)
- 📋 Data Tables dengan fitur filter dan export
- 🎨 UI/UX Modern dengan TailwindCSS
- 📱 Responsive Design
- 🔐 Autentikasi JWT dan Otorisasi

## Tech Stack

### Backend
- **Framework**: Laravel 10
- **PHP Version**: ^8.1
- **Database**: MySQL/PostgreSQL (configured via .env)
- **Authentication**: JWT Authentication

### Frontend
- **Framework**: Vue 3 (Composition API)
- **State Management**: Pinia
- **Router**: Vue Router 4
- **UI Components**: 
  - Headless UI
  - TailwindCSS
  - ApexCharts
  - FullCalendar
  - SweetAlert2
  - Tippy.js
  - Swiper
- **Form Handling**: 
  - Vee-Validate
  - Vuelidate
- **Build Tool**: Vite
- **TypeScript**: ✅

## 📋 Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js >= 16.x
- NPM atau Yarn
- MySQL/PostgreSQL
- Web Server (Apache/Nginx)

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/adityagus/fixed-asset.git
cd fixed-asset
```

### 2. Install Dependencies

#### Backend Dependencies
```bash
composer install
```

#### Frontend Dependencies
```bash
npm install
# atau
yarn install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan dengan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fixed_asset
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Jalankan Migrasi Database

```bash
php artisan migrate
```

Jika ingin menggunakan data dummy:
```bash
php artisan migrate --seed
```

### 6. Build Assets

#### Development
```bash
npm run dev
```

#### Production
```bash
npm run build
```

### 7. Jalankan Server

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 📝 Available Scripts

### Backend (Laravel)
```bash
# Jalankan development server
php artisan serve

# Jalankan migrasi
php artisan migrate

# Rollback migrasi
php artisan migrate:rollback

# Refresh database
php artisan migrate:fresh

# Run tests
php artisan test
```

### Frontend (Vue)
```bash
# Development server dengan hot reload
npm run dev

# Build untuk production
npm run build

# Watch mode dengan memory optimization
npm run watch
```

## 📁 Struktur Folder

```
fixed-asset/
├── app/                    # Laravel application logic
│   ├── Http/
│   ├── Models/
│   └── ...
├── bootstrap/              # Framework bootstrap files
├── config/                 # Configuration files
├── database/               # Database migrations & seeders
│   ├── migrations/
│   └── seeders/
├── public/                 # Public assets
├── resources/              # Frontend resources
│   ├── js/                 # Vue.js application
│   ├── css/                # Styles
│   └── views/              # Blade templates
├── routes/                 # Application routes
│   ├── api.php
│   └── web.php
├── storage/                # Storage for files
├── tests/                  # Unit & Feature tests
├── .env.example            # Environment variables example
├── composer.json           # PHP dependencies
├── package.json            # Node dependencies
├── tailwind.config.cjs     # TailwindCSS configuration
├── tsconfig.json           # TypeScript configuration
├── vite.config.ts          # Vite configuration
└── vercel.json            # Vercel deployment config
```

## 🔧 Konfigurasi

### TailwindCSS
Konfigurasi TailwindCSS dapat ditemukan di `tailwind.config.cjs`. Custom theme dan plugins dapat ditambahkan di sini.

### Vite
Konfigurasi build tool Vite ada di `vite.config.ts`.

### TypeScript
Konfigurasi TypeScript ada di `tsconfig.json` dan `tsconfig.node.json`.

## 🚢 Deployment

### Vercel
Aplikasi ini sudah dikonfigurasi untuk deployment di Vercel. File konfigurasi ada di `vercel.json`.

```bash
# Install Vercel CLI
npm install -g vercel

# Deploy
vercel
```

### Traditional Hosting
1. Build production assets: `npm run build`
2. Upload semua file ke server
3. Set document root ke folder `public/`
4. Pastikan `.env` sudah dikonfigurasi
5. Jalankan `composer install --optimize-autoloader --no-dev`
6. Jalankan `php artisan migrate --force`
7. Set permissions untuk folder `storage/` dan `bootstrap/cache/`

## 🔐 Security

- Gunakan HTTPS di production
- Jangan commit file `.env`
- Aktifkan CSRF protection
- Gunakan strong password untuk database
- Implementasikan rate limiting
- Regular update dependencies

## 📱 Fitur Khusus

### QR Code Scanner
Aplikasi dilengkapi dengan QR Code scanner menggunakan `html5-qrcode` untuk tracking aset dengan mudah.

### Export Data
Mendukung export data ke Excel menggunakan `vue3-json-excel`.

### Charts & Visualization
Visualisasi data menggunakan ApexCharts untuk dashboard analytics.

