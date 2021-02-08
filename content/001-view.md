# View

**View** merupakan huruf kedua dari akronim M-V-C. Berbeda dengan Model dan Controller yang berisi kode PHP, di View kita akan lebih banyak berurusan dengan HTML dan teman-temannya. Istilah kerennya, View adalah _presentation layer_, yaitu suatu bagian yang tugasnya melakukan presentasi (menyampaikan informasi) ke pengguna aplikasi.

Sebuah View yang _clean_ sama pentingnya dengan Controller dan Model yang _clean_. Bahkan View harusnya lebih mendapat prioritas karena "merapikan" View jauh lebih mudah dilakukan dibanding merapikan Controller atau Model.

Yuk, kita buktikan!

## Memecah File
Salah satu kemampuan yang harus dikuasai untuk menulis kode yang _friendly_ adalah keberanian untuk memecah kode atau _file_. 

Di awal proyek, semua masih terlihat rapi. Kodenya masih sedikit. Seiring berjalannya waktu, ada penambahan fitur disana-sini, tambal sulam _bug_ di kanan dan di kiri. Kode yang awalnya masih terlihat dalam satu layar sekarang harus di-_scroll_ berkali-kali untuk melihat keseluruhan isinya.

Programmer yang baik tahu kapan harus berhenti sejenak, mendeteksi bagian mana yang mulai membengkak dan berpotensi menyulitkan untuk dibaca di kemudian hari, lalu mulai memecahnya. View sengaja dijadikan topik pertama karena memecah view paling mudah dilakukan dan hampir tidak ada efek sampingnya.

> Keuntungan  memecah baris kode yang besar menjadi beberapa file kecil akan semakin terasa berlipat ganda ketika kamu bekerja dalam sebuah tim yang menerapkan _version control system_ seperti Git.

## Biasakan Memakai Sub View

Bayangkan kamu mendapat tugas untuk membuat dashboard dengan mockup seperti di bawah ini.
![](assets/img/dashboard.png)

Pada umumnya, tampilan di atas akan diimplementasi menjadi file blade seperti berikut:

```php
@extends('layout')

@section('content')
    <h1>Statistik Laporan</h1>
    <section>
        ...
        Summary
        ...
    </section>
    <section>
        ...
        Chart
        ...
    </section>
    <section>
        ...
        List
        ...
    </section>
@endsection
```
Sekarang mari kita coba untuk memecahnya menjadi _sub view_. Bagaimana caranya?

Secara kasat mata, kita bisa melihat ada tiga komponen utama yang menyusun halaman dashboard di atas, yaitu:
1. Summary
2. Chart
3. List

Setelah mengetahui komponen penyusun halaman dashboard tersebut, langkah berikutnya adalah membuat _**sub view**_ untuk masing-masing komponen:
1. `_summary.blade.php`
2. `_chart.blade.php`
3. `_list.blade.php`

Lalu, kamu cukup memanggil tiap komponen dengan **@include**:
```php
@extends('layout')

@section('content')

    <h1>Dashboard</h1>
    
    @include('_summary')
    @include('_chart')
    @include('_list')
    
@endsection
```
Alih-alih punya satu _file_ yang berisi 100 baris kode, sekarang kamu punya 4 _file_ yang masing-masing berisi 25 baris kode. Lebih rapi dan lebih ringan ketika dibuka di _code editor_ atau IDE.

> Kode yang baik adalah kode yang mencerminkan kebutuhan fungsional aplikasinya. Maksudnya adalah ketika kita bilang ada fitur a, b, dan c di aplikasi, maka a, b, dan c itu idealnya juga terlihat secara eksplisit di kode penyusun aplikasi, entah itu sebagai nama _file_, nama fungsi, atau nama Class. 

## Penamaan Sub View
Kamu mungkin bertanya kenapa _file_ _blade_ pada contoh sebelumnya diberi nama `_summary.blade.php` (perhatikan ada _underscore_ diawalnya) dan bukan `filter.blade.php` saja. 

Dengan menambahkan _underscore_ sebagai prefiks, maka kita bisa melihat dengan jelas mana _view_ utama dan mana _sub view_. Editor yang kamu pakai secara otomatis akan mengurutkan _file_ secara alfabetis dan seolah-olah mengelompokkan _file_ menjadi dua bagian: bagian atas untuk _sub view_ dan bagian bawah untuk _view_ utama.

![](assets/img/naming-subview.png)

Secara sekilas kita bisa melihat bahwa _sub view_ yang diberi prefiks lebih mudah dikenali dibanding yang tanpa prefiks. _Minimum effort, maximum effect_.

> Lebih jauh lagi, kamu juga bisa membuat folder baru untuk meletakkan _sub view_. Nama yang umum dipakai biasanya **partials** atau **sub**. Kalau sudah dibuatkan folder khusus untuk menampung _sub view_, maka nama filenya tidak perlu lagi diberi prefiks "_" (_underscore_). 
> 
> **Ingat prinsipnya, kelompokkan yang sejenis**.

## Layout vs Konten
Setelah paham kapan harus mulai memecah _view_ agar tidak membengkak, selanjutnya kita perlu paham **dimana** sebuah _view_ harus dipecah. Terkadang <del>gambar</del> kode bisa menggantikan 1000 kata, jadi mari kita lihat contohnya.

Anggap kita sedang mengerjakan aplikasi menggunakan Bootstrap. Lalu tampilan yang ingin dibuat adalah seperti di bawah ini:

![](assets/img/grid.png)

Jika tidak hati-hati, maka _view_ yang kamu buat akan seperti ini:
```html
<div class="container">
  <div class="row">
    @include('_weather')
    @include('_profile-stat')
    @include('_blog')
    @include('_profile-full')
    @include('_calendar')
    @include('_searchbox')
    @include('_expertise')
    @include('_inbox')
    @include('_todo')
  </div>
</div>
```
Apa yang salah dari kode di atas?

Kita kehilangan informasi tentang susunan grid.  Melihat kode seperti di atas, susah untuk membayangkan bagaimana hasil _rendering_ halaman tanpa melihat langsung di _browser_ atau melihat satu persatu isi setiap _sub view_.

Cara yang lebih baik adalah dengan meng-**eksplisit**-kan struktur grid di _view_ utama.

```html
<div class="container">
  <div class="row">
    <div class="col-sm-4">
        @include('_weather')
        @include('_profile-stat')
        @include('_blog')
    </div>
    <div class="col-sm-4">
        @include('_profile-full')
        @include('_calendar')
    </div>
    <div class="col-sm-4">
        @include('_searchbox')
        @include('_expertise')
        @include('_inbox')
        @include('_todo')
    </div>        
  </div>
</div>
```

Sekarang terlihat dengan jelas bahwa halaman di atas terbagi menjadi 3 kolom. Dengan meletakkan tag HTML untuk _layouting_ di _view_ utama, kamu bisa mengganti susunan layout dengan sangat mudah. Cukup utak-atik posisi **@include**. Sub view tidak perlu diubah.

>  **_View_ utama untuk mengatur layout, _sub view_ untuk merender konten**. Ketika melihat _view_ utama, pastikan kamu bisa membayangkan bagaimana layout halamannya.



## Tidak Mencampur PHP dan JS
Pemakaian editor atau IDE sudah menjadi hal yang wajar bagi programmer saat ini. Setiap keyword, setiap bahasa pemrograman bisa kamu atur _syxtax higlighting_-nya untuk memudahkan mengenali kode.

//TODO gambar syntax higlighting bisa mengenali error

Mencampur kode PHP dan Javascript (berlaku juga untuk bahasa lain) akan mengurangi readability (seberapa mudah kode dibaca/dipahami) dan kemampuan editor/IDE untuk menganalisis kode.

//TODO gambar contoh kode blade + JS campur

Ketika kebutuhan aplikasi mengharuskan adanya "interaksi" antara kode Blade(PHP) dan Javascript, misalnya variable Javascript yang berasal dari variable PHP, ada dua cara yang bisa dilakukan:
1. Passing sebagai data-attribute
2. Definisikan _dynamic variable_ di awal kode

### Passing Variable Sebagai `data-attribute`

// TODO contoh kode

Dengan metode penulisan seperti di atas, kita bisa meminimalisir adanya _**kode oplosan**_, yaitu suatu kondisi bercampurnya 2 bahasa dalam **satu baris kode**.

Dilihat dari kaca mata _resources_, _context switching_ itu mahal. Berpindah dari mode PHP ke mode Javascript juga sama. Oleh sebab itu penting bagi kita untuk bisa mengelompokkan masing-masing kode ke dalam "blok"-nya masing-masing.

> _**Context switching**_ adalah sebuah kondisi ketika kita harus berpindah dari satu aktivitas ke aktivitas lain. 
> <br>
> <br>
> Contohnya sama seperti saat kamu membaca buku ini. Setiap selesai satu bagian kamu pegang _hape_, buka notififikasi, membalas komentar, lalu kembali melanjutkan membaca buku. Ada sekian detik waktu tambahan yang dibutuhkan otak kita untuk kembali fokus ke aktivitas membaca buku.
> <br>
> <br> 
> _**Context switching**_ dalam waktu yang singkat dengan intensitas yang tinggi sangat mengganggu produktivitas dan proses belajar hal baru. Hindarilah semaksimal mungkin!

### Definisikan  _Dynamic Variable_ Di Awal Kode

Jika karena suatu hal metode sebelumnya tidak bisa diterapkan, maka opsi lainnya adalah dengan mendefinisikan semua variabel di awal dengan _keyword_ `let` ataupun `const`.

//TODO contoh kode
 
Sekali lagi, kata kuncinya adalah **pengelompokkan**. Sekarang kita punya satu blok kode yang khusus menjadi tempat perantara antara PHP dan Javascript. Kurang ideal, tetapi tetap lebih rapi dibanding membiarkan kode PHP bercampur dengan Javascript, berserakan di sembarang tempat.

> **Idealisme vs kompromi**
> 

## Jangan Pisahkan JS dan Pasangan HTML-nya

Kasus yang sering ditemui ketika koding di View adalah menambahkan Javascript untuk membuat halaman yang lebih interaktif. 

Contoh pertama, menggunakan halaman dashboard sebelumnya, ternyata perlu ada tambahan tombol "Export Excel" di bagian Tabel.



Contoh kedua, kita mau menambahkan filter dengan mekanisme Ajax agar tidak perlu *refresh* halaman. Kira-kira alur kodenya seperti ini: 

1. Tambahkan event onclick di tombol "Tampilkan"
2. Request ke server via Ajax
3. Update *chart*
4. Update tabel

Karena aksi ini melibatkan beberapa sub view, maka lebih tepat jika kode Javascriptnya diletakkan di view utama.

//TODO skeleton kode

Untuk memudahkan pembacaan kode, maka disarankan untuk menambahkan *identifier* di view utama, misalnya menggunakan atribut **"id"** yang berfungsi sebagai "rambu penunjuk arah". Jadi, ketika nanti ada programmer yang membaca kode Javascript, dia bisa langsung menentukan pasangan kode HTML-nya ada di sub view yang mana tanpa harus menelusuri satu per satu.



> :bulb: Ada dua prinsip penting yang harus dibiasakan untuk bisa menulis kode yang rapi:
>
> 1. **Memecah** yang besar menjadi beberapa bagian kecil.
> 2. **Dekatkan** yang saling membutuhkan. 
>
> Resapi, pahami, praktekkan, dan biasakan. Prinsip diatas berlaku di semua bahasa pemrograman dan framework.



## View Composer Terlalu Magic, Hindari!

Pernahkan kamu mengalami sebuah momen dimana ketika sedang asik-asiknya _debugging_, lalu menemukan sebuah variabel, misalnya `$kategori`, tetapi tidak menemukan dari mana asal variabel tersebut. Tidak ada di Controller, tidak ada juga di View.

//TODO contoh kode view composer
//TODO contoh kode controller (tidak ada passing variabelnya)

### Apa Alternatifnya?

Ada satu fitur di Laravel yang menurut saya sangat jarang dipakai, yaitu **Service Injection**.  

//TODO contoh kode implementasi $kategori dengan service injection

Dokumentasi resminya bisa dibaca di https://laravel.com/docs/master/blade#service-injection.

Apa yang dituliskan secara eksplisit biasanya lebih mudah dibaca dan dipahami. Oleh sebab itu, eksplisitkanlah pemanggilan variabel global di View dengan menggunakan **Service Injection**.

Beberapa manfaat yang bisa kita dapat ketika menerapkan Service Injection antara lain:
1. Memaksa programmer membuat Class khusus untuk membungkus _logic_ mendapatkan variabel. Disini, kita sekaligus belajar menerapkan Single Responsibility Principle.
2. Karena _logic_ ada di sebuah Class, maka menjadi lebih mudah dites, dibandingkan jika logic tersebut ada di Service Provider. Btw, pernah melakukan unit test terhadapt Service Provider di Laravel?

//TODO gambar struktur folder ServiceInjection


### Boleh, Asalkan...

#### 1. Didokumentasikan Secara Eksplisit
Biasakan mengomentasi bagian kode yang "_magic_" untuk membantu programmer lain (atau dirimu sendiri, 3 bulan kemudian) ketika membacanya:

```php
//layout.blade.php

//@kategori berasal dari ViewComposerServiceProvider
@foreach($kategori as $item)
...
@endforeach
```

> **Bukankah manusia tempatnya lupa?**

#### 2. Sudah Ada Konvensi
Sudah ada kesepakatan antar anggota tim yang diambil sebelumnya, bahwa semua variabel global yang ditemukan di  View pasti berasal dari `ViewComposerServiceProvider`. Tapi ingat, konvensi tanpa dokumentasi juga rawan dilupakan. Oleh sebab itu, tulislah semua konvensi di `readme.md`.

> Silakan googling dengan kata kunci "_readme driven development_".

#### 3. Buat Service Provider Terpisah
View Composer biasanya diletakkan di `AppServiceProvider`, sesuai contoh di dokumentasi resmi Laravel. Seiring berjalannya waktu, biasanya AppServiceProvider ini menjadi _**God Object**_ dan kodenya membengkak. Oleh sebab itu, buat dan daftarkanlah Service Provider baru, misalnya `ViewComposerServiceProvider`, khusus untuk mendaftarkan variabel global ke View.

<hr>

> Apa yang ditulis dengan cepat, apalagi tidak ditulis sama sekali (implisit), biasanya hanya bisa dibaca dengan lambat (ehem, resep dokter).
> Sebaliknya, apa yang eksplisit (lebih butuh waktu untuk ditulis) biasanya lebih cepat dibaca. Untuk kasus ini eksplisit > implisit.