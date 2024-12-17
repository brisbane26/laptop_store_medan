## Laptop Store Medan

## Info

<p>Nama aplikasi: Laptop Store Medan</p>
<p></p>Tim pengembang: Kelompok 2</p>

- Brisbane Jovan Rivaldi Sihombing - 231402001<br> 
- Petra Igor Keliat- 231402070<br>
- Pangeran Rae Ebenezer Siahaan - 231402079<br>
- Jesica Eldamaris Maha- 231402101<br>  
- Fatimah Az zahra - 231402104<br>  

## Desc

Laptop Store Medan adalah aplikasi web yang dibuat menggunakan framework Laravel yang dibuat untuk mencari dan memesan segala kebutuhan tentang laptop.

## Features
- Login & Register
- Forgot Password
- Level User

1.	Costumer
- melihat product.
- mencari product.
- menambahkan keranjang.
- melihat daftar product yang masuk ke keranjang.
- checkout barang yang ada pada cart.
- melihat detail pemesanan.
- melakukan pembayaran.
- melihat status pesanan.
- melakukan pemesanan service.
- melihat Riwayat pesanan.
- mendownload struk pesanan.

2.	Admin
- melihat peningkatan penjualan (dashboard).
- melihat user.
- melihat daftar product.
- mencari product.
- mengelola product.
- mengelola service.
- melihat daftar pesanan.
- melihat Riwayat pesanan.
- mengkonfirmasi pesanan.

3.	Owner
- melihat peningkatan penjualan (dashboard).
- melihat product.
- mengelola product.
- melihat peningkatan penjualan.
- mengelola admin.
- mengelola costumer.
- melihat daftar pesanan.
- melihat Riwayat pesanan.
- melihat Logs Admin.


## Installation

To run Laptop Store Medan di device anda, lakukan:


1. Clone this repository:

   ```bash
   git clone https://github.com/brisbane26/laptop_store_medan.com
   ```
2. Change to the project directory
    ```bash
    cd laptop_store_medan
    ```
3. Install the project dependencies
    ```bash
    composer install
    npm install
    ```
4. Copy the .env.example file to .env and configure your environment variables, including your database settings and any other necessary configuration.
    ```bash
    copy .env.example .env
    ```
5. Generate an application key
    ```bash
    php artisan key:generate
    ```

6. Create a symbolic link for the storage directory
   ```bash
   php artisan storage:link
   ```
7. Set the filesystem disk to public in the .env file
   ```bash
   FILESYSTEM_DISK=public
   ```
8. Create your own API Key on RajaOngkir and set API_RAJAONGKIR in the .env
   ```bash
    API_RAJAONGKIR=7be3da5344308d8be3d76141bff11f72
     ```
9. Migrate the database
    ```bash
    php artisan migrate
    ```
10. Seed the database with sample data (optional):
    ```bash
    php artisan db:seed RoleSeeder
    ```
11. Seed the database with sample data (optional):
    ```bash
    php artisan db:seed
    ```
    
12. Start the development server
    ```bash
    php artisan serve
    ```
13. Access the application in your browser at http://localhost:8000


## Technologies
- PHP 
- HTML, CSS, JavaScript, Blade
- Laravel
- Laragon/XAMPP
