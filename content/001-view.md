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

Pada umumnya, tampilan di atas akan diimplementasi menjadi satu file blade seperti berikut:

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



## Tidak Menyisipkan Blade Ke Dalam Javascript
Mengembangkan aplikasi web tidak bisa lepas dari Javascript. Begitu juga dengan aplikasi Laravel yang dikembangkan secara _fullstack_.

```html
<section>
    <button id="buttonSubmitComment">Kirim Komentar</button>
</section>

<script>
$('#buttonSubmitComment').on('click', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: {{ route('comment.store') }},
        type: "POST",
        dataType: 'json',
    })
});
<script>
```

_Mengoplos_ kode PHP dan Javascript seperti contoh di atas setidaknya memiliki dua kekurangan:
1. Membaca 2 _syntax_ dari 2 bahasa yang berbeda dalam satu blok kode yang sama akan sedikit merepotkan otak (_context switching_) dan berpotensi menimbulkan kesalahan dasar ketika menulisnya. 

    ```javascript 
     url: {{ route('comment.store') }},
    ```
    Berapa detik yang kamu butuhkan untuk menyadari bahwa potongan kode di atas salah secara sintaksis?
    
1. Jika suatu ketika kamu ingin memindahkan semua  _script_ dari file Blade ke satu file `js`, maka tidak bisa dilakukan secara langsung karena fungsi `route()` tidak akan dikenali di file `js`. Harus di-_refactor_ dulu.

Ketika kebutuhan aplikasi mengharuskan adanya interaksi antara kode Blade(PHP) dan Javascript,  ada dua cara yang bisa dilakukan agar hubungan tersebut bisa langgeng dalam jangka panjang (mudah di-_maintain_):
1. Passing sebagai data-attribute
2. Definisikan _dynamic variable_ di awal kode


> Sekedar mengingatkan, kode `url: {{ route('comment.store') }}` di atas salah karena kurang tanda petik. Kode yang benar seharusnya `url: "{{ route('comment.store') }}"`


### Passing Variable Sebagai Atribut HTML data-*

Alih-alih mencampur Blade dan Javascript, kamu bisa memanfaatkan atribut HTML `data-*` untuk mem-_passing_ sebuah value yang berasar dari PHP agar bisa dibaca oleh Javascript.

```html
<section>
    <button id="buttonSubmitComment" data-url="{{ route('comment.store') }}">Kirim Komentar</button>
</section>

<!-- End of Blade here -->
<!-- Dari baris ini ke bawah khusus Javascript -->

<script>
$('#buttonSubmitComment').on('click', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).data('url'),
        type: "POST",
        dataType: 'json',
    })
});
<script>
```
Atribut `data-*` merupakan atribut HTML5 yang valid digunakan untuk semua elemen. Artinya kamu bisa menambahkan `data-*` ke `<form>`, `<button>`, `<table>`, dan semua tag HTML lain.

Cara mengaksesnya juga sangat mudah. 
```javascript
// Dengan Javascript native
document.querySelector('#buttonSubmitComment').dataset.url;

// Dengan jQuery
$('#buttonSubmitComment').data('url');
```

Dengan metode penulisan seperti di atas, kamu telah berhasil menjauhkan diri dari _**kode oplosan**_, yaitu suatu kondisi bercampurnya 2 bahasa dalam **satu blok kode**.

#### Sekilas Tentang _Context Switching_

_**Context switching**_ adalah sebuah kondisi ketika kita harus berpindah dari satu aktivitas ke aktivitas lain. 

Dilihat dari kaca mata _resources_, _context switching_ itu mahal. Berpindah dari mode PHP ke mode Javascript juga sama. Oleh sebab itu penting bagi kita untuk bisa mengelompokkan masing-masing kode ke dalam "blok"-nya masing-masing.

Contohnya sama seperti saat kamu membaca buku ini. Setiap selesai satu bagian kamu pegang _hape_, buka notififikasi, membalas komentar, lalu kembali melanjutkan membaca buku. Ada sekian detik waktu tambahan yang dibutuhkan otak kita untuk kembali fokus ke aktivitas membaca buku.

> _**Context switching**_ dalam waktu yang singkat dengan intensitas yang tinggi sangat mengganggu produktivitas dan dan tidak baik untuk kesehatan mental. Hindarilah semaksimal mungkin!
> 
> Referensi: https://blog.rescuetime.com/context-switching/

### Definisikan  _Dynamic Variable_ Di Awal Kode

Jika karena suatu hal metode sebelumnya tidak bisa diterapkan, maka opsi lainnya adalah dengan mendefinisikan semua variabel di awal dengan _keyword_ `let` ataupun `const`.

```html
<section>
    <button id="buttonSubmitComment">Kirim Komentar</button>
</section>


<script>

<!-- Area transisi, serah terima antara Blade dan Javascript -->
const URL = '{{ route('comment.store') }}';
const sampleData = @json($dataFromController);

<!-- Setelah ini full Javascript, tidak ada lagi oplosan -->
$('#buttonSubmitComment').on('click', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: URL,
        type: "POST",
        dataType: 'json',
    })
});
<script>
```

Sekali lagi, kata kuncinya adalah **pengelompokkan**. Sekarang kita punya satu blok kode yang khusus menjadi tempat perantara antara PHP dan Javascript. Kurang ideal, tetapi tetap lebih rapi dibanding membiarkan kode PHP bercampur dengan Javascript, berserakan di setiap baris.


> _Fullstack application_ merujuk ke aplikasi yang _backend_ dan _frontend_ tergabung dalam satu _codebase_. Alternatifnya, _backend_ memiliki _codebase_ sendiri (misalnya memakai Java) dan _frontend_ memiliki _codebase_ sendiri (misalnya memakai Vue.js).

## Jangan Pisahkan JS dan Pasangan HTML-nya

>{quote} Javascript dan HTML ibarat sepasang penganti baru, susah dipisahkan, inginnya berdekatan terus. Itu sudah sifat alamiah mereka.

Di bagian sebelumnya, kita sudah mengenal cara memecah satu file View yang besar menjadi beberapa _sub view_ yang kecil. Nah, kamu harus berhati-hati ketika melakukan pemecahan tersebut. Pastikan JS dan HTML yang saling berhubungan tetap berada dalam satu file yang sama.

Contoh, 
```html

```

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



## View Share & View Composer Terlalu Magic, Hindari!

Pernahkan kamu mengalami momen dimana sedang asik  _debugging_, lalu menemukan sebuah variabel, misalnya `$categories`, tetapi tidak menemukan dari mana asal variabel tersebut. Tidak ada di Controller, tidak ada juga di View.

Kode seperti itu umum dijumpai di file Blade untuk _layouting_.

```html
<!-- resources/view/layout.blade.php -->
<html>
    <head>
        <title>The Boring Stack</title>
    </head>
    <body>
        <header>
            @foreach($categories as $item)
            <a href="{{ $item->permalink }}">{{ $item->title }}</a>
            @endoreach
        </header>
        {{ $slot }}
    </body>
</html>
```

Semua yang melihat file di atas tentu bertanya-tanya, dari mana asalnya variabel `$categories`.  Perlu beberapa saat sebelum kamu menyadari bahwa ini adalah salah satu _**magic**_  dari Laravel.

### View Share & View Composers
Jika ada variabel yang dibutuhkan di semua halaman, kamu bisa melakukannya dengan dua cara. 

Pertama dengan memanfaatkan `View::share`:
```php
use Illuminate\Support\Facades\View;

View::share('categories', Category::all());
```
Kedua dengan memakai View Composers:
```php
View::composer('layout', function ($view) {
    $view->with('categories', Category::all());
});
```

Keduanya sama, secara _magic_ mendaftarkan variabel baru yang bisa diakses dari semua View. Jika bukan kamu sendiri yang menulis kode-kode di atas, besar kemungkinan akan kesulitan ketika harus melacak asal muasalnya di kemudian hari.

> Dokumentasi tentang **View Share** dan **View Composers** bisa dibaca di https://laravel.com/docs/master/views#view-composers.

### Apa Alternatifnya?

Ada satu fitur di Laravel yang harusnya lebih manusiawi jika dipakai untuk mendaftarkan variabel ke View, yaitu **Service Injection**.  

Sekarang mari kita implementasikan kasus `$categories` dengan Service Injection:
```html
@inject('site', 'App\Services\SiteService')

<html>
    <head>
        <title>The Boring Stack</title>
    </head>
    <body>
        <header>
            @foreach($site->categories() as $item)
            <a href="{{ $item->permalink }}">{{ $item->title }}</a>
            @endoreach
        </header>
        {{ $slot }}
    </body>
</html>
```

Sekarang kodenya terlihat lebih eksplisit dan natural. Ketika melihat `$site->categories()` secara otomatis kita akan mencari `$site` di file yang sedang dibuka saat ini (`layout.blade.php`).  Ketika menemukannya di baris pertama, terlihat jelas **petunjuknya** kemana `$site` ini mengarah. 

Tidak perlu lagi menerka-nerka, `dd()`, atau bertanya ke programmer lain. Petunjuknya sudah sangat jelas. 

> Dokumentasi resmi tentang **Service Injection** bisa dibaca di https://laravel.com/docs/master/blade#service-injection.

Pilihan lain yang lebih tepat adalah membuat **Blade Component** khusus untuk me-render kategori.
```html
<!-- resources/views/layout.blade.php -->
<!-- Lihat betapa bersihnya kode HTML jika memakai Blade Component       -->
<html>
    <head>
        <title>The Boring Stack</title>
    </head>
    <body>
        <header>
            <x-categories /> 
        </header>
        {{ $slot }}
    </body>
</html>
```

```php
<?php

// app/View/Components/Categories.php

namespace App\View\Components;

use Illuminate\View\Component;

class Categories extends Component
{
    public function render()
    {
        return view('components.categories');
    }
}
```

Yang perlu menjadi perhatian, konvensi penamaan komponen harus konsisten dan mengikuti standard Laravel.

> Dokumentasi resmi tentang **Blade Component** bisa dibaca di https://laravel.com/docs/master/blade#components.

### Keuntungan
Apa yang dituliskan secara eksplisit biasanya lebih mudah dibaca, dipahami, dan  diikuti alurnya. Oleh sebab itu, eksplisitkanlah pemanggilan variabel global di View dengan menggunakan **Service Injection** atau **Blade Component**.

Beberapa manfaat yang bisa kita dapat ketika menerapkannya antara lain:
1. Memaksa programmer membuat Class khusus untuk membungkus _logic_ mendapatkan variabel. Disini, kita sekaligus belajar menerapkan Single Responsibility Principle.
2. Karena _logic_ ada di sebuah Class, maka menjadi lebih mudah dites, dibandingkan jika logic tersebut ada di Service Provider.

> **_Single Responsibility Principle (SRP)_** adalah salah satu kaidah menulis _clean code_ dimana sebuah Class harus fokus dengan satu tugas khusus, tidak boleh terlalu kompleks atau multi fungsi. **S**RP merupakan huruf pertama **(S)** dari akronim **SOLID** yang sangat tersohor itu.

### Boleh, Asalkan...

#### 1. Didokumentasikan Secara Eksplisit
Biasakan mengomentasi bagian kode yang "_magic_" untuk membantu programmer lain (atau dirimu sendiri, 3 bulan kemudian) ketika membacanya:

```html
<!-- layout.blade.php -->

<!-- @kategori berasal dari ViewComposerServiceProvider -->
@foreach($kategori as $item)
...
@endforeach
```

> Ikatlah ilmu (_knowledge_) dengan mencatatnya, termasuk dengan menulis komentar yang tepat. **Bukankah manusia tempatnya lupa?**

#### 2. Sudah Ada Konvensi
Sudah ada kesepakatan antar anggota tim yang diambil sebelumnya, bahwa semua variabel global yang ditemukan di  View pasti berasal dari `ViewServiceProvider`. Tapi ingat, konvensi tanpa dokumentasi juga rawan dilupakan. Oleh sebab itu, tulislah semua konvensi di `readme.md`.

> File _readme.md_ harusnya bisa menjadi sumber utama _knowledge_ terkait _source code_ aplikasi. Oleh sebab itu, rajin-rajinlah mencatat di `readme.md`, atau istilah kerennya "_readme driven development_".

## Sub View vs Blade Component
Semua cara yang telah disebutkan sebelumnya merupakan cara singkat dan praktis untuk menjaga agar tidak ada penumpukan kode di satu _file_. Level selanjutnya, kamu bisa membuat sub view tersebut _reusable_, bisa digunakan di tempat lain, oleh programmer lain, dengan mudah. Caranya adalah dengan memanfaatkan **Blade Component**.

Secara singkat, perbedaan Sub View dan Blade Component bisa dilihat dalam tabel berikut:


| Sub View                                                                                                     |Blade Component                                                    |
| -------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------ |
| Mudah diterapkan karena hanya berurusan dengan file Blade                                                    |Sedikit lebih kompleks, karena harus membuat class PHP             |
| Tidak perlu memikirkan _abstraction_                                                                         |Merupakan salah satu bentuk penerapan _abstraction_                |
| Ketika membuat sub view, kita tidak perlu berpikir _reusable_                                                |Didesain untuk _reusable_ , bisa dipakai di view lain dengan mudah |
| Analoginya seperti _protected method_ , hanya digunakan untuk _scope_ tertentu                               |Seperti _public method_ , penerapannya lebih luas dan generik      |
| Tidak perlu memikirkan passing parameter karena sub view otomatis bisa mengenali variable dari _parent-view_- |Perlu meng-handle parameter                                        |

Terkadang, pilihannya bukan mana yang benar atau salah, tapi **mana yang lebih cocok** dengan kondisi tim.

Dalam dokumentasi resmi Laravel terkait Blade Component, https://laravel.com/docs/master/blade#components,  disebutkan: " _Components and slots provide similar benefits to sections, layouts, and includes; however, some may find the **mental model** of components and slots easier to understand_".


Kata kuncinya adalah **mental model**. Bagaimana kita mau memodelkan aplikasi. Bagaimana kita menerjemahkan kebutuhan bisnis menjadi struktur kode yang _long lasting_ dan tetap mudah di-_maintain_, tiga bulan lagi, 6 bulan lagi, bahkan bertahun-tahun dari sekarang.

>{quote} Good programmer write code for compiler, great programmer write code for human.