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

## Jangan Hanya Menulis Kode, Rangkailah Sebuah Cerita!

Menulis kode untuk mesin (_compiler, interpreter_) relatif mudah. Mesin tidak punya emosi. Mesin tidak pernah lupa. Apa yang benar menurut mesin di hari ini, tetap akan dianggap benar tiga bulan kemudian. Mesin punya **konsistensi yang tinggi** dalam membaca dan menerjemahkan kode.

Sebaliknya, manusia punya perasaan dan ingatan yang terbatas. Apa yang menurut kita baik-baik saja di hari ini, bisa jadi berubah menjadi **_tragedi_** tiga bulan kemudian. 

Pernah mengalami momen dimana kamu mengumpat di dalam hati, #%^@$3M!**#&, ketika membaca sebuah kode?

Ini apa ya maksudnya?

Variabel ini dari mana asalnya?

Duh, muter-muter kodenya, capek debuggingnya!

Sejurus kemudian baru tersadar, ternyata itu kodemu sendiri. Kamu menulisnya tiga bulan yang lalu, ketika masih _**fresh**_ dengan masalah yang dihadapi. 

Sekarang, kamu sudah lupa. 

Membaca kode hanya memberikan potongan-potongan abstrak. C A minor D minor ke G ke C lagi, [lupa-lupa ingat](https://www.youtube.com/watch?v=89ME-x4iicw) alurnya.  _Debugging_ berasa seperti main [Minesweeper](https://minesweeper.online/).  Kodemu tidak mau berterus terang, tidak mau bercerita. 

Bukan salah kode. 

Tiga bulan yang lalu kamu memang hanya menulis potongan kode, **bukan cerita**.

## Bercerita Dengan Protected Method
Baris kode adalah buah pikir programmer yang menulisnya. Sama seperti cerpen yang dihasilkan seorang penulis. Sama seperti pidato atau video motivasi yang dihasilkan seorang _public speaker_.

Kamu. Iya, kamu.

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