\# Mobile Provider API

Bu proje, bir mobil sağlayıcı için fatura sistemi API\'sidir.

\## Özellikler

\- Abone kullanımı ekleme - Fatura hesaplama - Fatura sorgulama -
Detaylı fatura sorgulama - Fatura ödeme

\## Teknolojiler

\- Laravel 12 - MySQL - Laravel Sanctum (JWT kimlik doğrulama) -
L5-Swagger (API dokümantasyonu)

\## Veri Modeli

\## API Dokümantasyonu

API dokümantasyonuna şu adresten erişilebilir:
\[https://affectionate-solomon.213-238-168-122.plesk.page/api/documentation\]

\## Kurulum

\`\`\`bash \# Repoyu klonlayın git clone
https://github.com/teyfikaktas/mobile-provider-api.git

\# Proje dizinine gidin cd mobile-provider-api

\# Bağımlılıkları yükleyin composer install

\# .env dosyasını oluşturun cp .env.example .env

\# Uygulama anahtarı oluşturun php artisan key:generate

\# Veritabanı migrasyonlarını çalıştırın php artisan migrate

\# Örnek verileri ekleyin php artisan db:seed

\# Swagger dokümantasyonunu oluşturun php artisan l5-swagger:generate

\# Uygulamayı çalıştırın php artisan serve
