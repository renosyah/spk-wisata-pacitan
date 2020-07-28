# Project SPK wisata pacitan

## Kebutuhan

- PHP 7
- MysqlDB or MariaDB

## Cara Menjalankan

- buka cmd di direktori ini lalu ketik
    ```
        
        php -S {YOUR_IP}:80

    ```
    atau

     ```
        
        php -S localhost:80

    ```
- buka web browser dan menuju url `localhost:80`

## SPK API

* untuk pariwisata goa : http://localhost/api/all_data_pariwisata_spk_goa.php?kategori_id=1&fasilitas_id=1&min_tiket_masuk=5000&max_tiket_masuk=10000&min_jarak=5&max_jarak=25&min_umur=15&max_umur=20

* non-goa : http://localhost/api/all_data_pariwisata_spk.php?kategori_id=2&fasilitas_id=1&min_tiket_masuk=5000&max_tiket_masuk=10000&min_jarak=5&max_jarak=25