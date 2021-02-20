# Controller
Controller merupakan "tempat nongkrong" utama bagi programmer Laravel. Sebagian besar waktu koding akan dihabiskan disini. 

- Menyiapkan data untuk View.
- Memvalidasi form.
- Memproses submit form.
- Memanggil query builder.
- Memanggil Eloquent Model.
- Pengecekan hak akses.
- Ekspor ke PDF.
- Ekspor ke Excel.
- _Looping_, _if else_, dan _logic_ aplikasi akan sangat banyak ditemui di Controller.

Ketika kamu baru mengenal M-V-C, wajar jika pilihannya hanya terbatas pada ketiga komponen tersebut. Model untuk "simpan-pinjam" data dari database, View untuk menampilkan data, dan **Controller untuk sisanya**.

Tidak ada masalah untuk aplikasi skala kecil. Berpotensi obesitas (_Fat Controller_) untuk aplikasi skala menengah hingga besar.

Pada bab ini, kita akan belajar **hal-hal kecil dan mudah** yang bisa dilakukan untuk membuat Controller tetap _slim_ dan mudah dipahami.

## Bercerita Dengan Protected Method
protected method adalah outline (daftar isi)
ingat rumusnya, 

```php
contoh kode fat controller
```

## Reusable Dengan Trait
protected method hanya dikenali dalam Class yang sama
jika butuh lintas Class, manfaatkan Trait

## Pasti Sama Dengan Base Class
Jika butuh pengakuan (identitas), terapkan Inheritance.

## Berkomitmen Dengan Resource Controller
Jadikan 7 kata ajaib sebagai patokan dalam menerjemahkan fitur menjadi kode
Jika bingung, limpahkan ke Single Action Controller

##  Single Action Controller Untuk "Sisanya"
Kata kerja (action)
contoh:
- redirect setelah login
- download pdf
- logout

## Dimana Pengecekan Hak Akses?

## FormRequest Yang Terabaikan

## Kapan Perlu Membuat Service Class?