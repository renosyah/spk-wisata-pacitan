CREATE TABLE kategori (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama TEXT
);


CREATE TABLE data_pariwisata(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT(11) NOT NULL,
    nama TEXT,
    lokasi TEXT,
    jarak float,
    deskripsi TEXT,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id)
);


CREATE TABLE fasilitas (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama TEXT
);



CREATE TABLE fasilitas_pariwisata(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fasilitas_id INT(11) NOT NULL,
    data_pariwisata_id INT(11) NOT NULL,
    FOREIGN KEY (fasilitas_id) REFERENCES  fasilitas(id),
    FOREIGN KEY (data_pariwisata_id) REFERENCES  data_pariwisata(id)
);



CREATE TABLE umur (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    data_pariwisata_id INT(11) NOT NULL,
    umur INT(11),
    FOREIGN KEY (data_pariwisata_id) REFERENCES  data_pariwisata(id)
);



CREATE TABLE tiket_masuk (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    data_pariwisata_id INT(11) NOT NULL,
    harga INT(11),
    FOREIGN KEY (data_pariwisata_id) REFERENCES data_pariwisata(id)
);


