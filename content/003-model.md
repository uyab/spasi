# Model

## Tetap Rapi Dengan Trait

Ketika membahas Controller pada bab sebelumnya, kita sudah memanfaatkan Trait untuk menghindari duplikasi atau biasa disebut dengan *code reuse*. Pada prakteknya Trait juga bisa digunakan untuk keperluan yang lain, salah satunya adalah mengelompokkkan *method* yang sejenis.

Contoh, ketika membuat Eloquent Model, beberapa jenis *method* yang sering dibuat adalah terkait *relationship*, *setter getter*, dan *scope*. 

Mari kita lihat contoh kode berikut:

```php
class User extends Model
{
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getFullnameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function setFirstnameAttribute($name)
    {
        $this->attributes['firstname'] = ucfirst($name);
    }

    public function scopeIdle($query)
    {
        return $query->whereDate('last_login', now()->subMonth(1));
    }
}
```

Jika kita kelompokkan berdasar jenisnya, maka potongan kode di atas memiliki pola:

- relationship
- relationship
- accessor
- relationship
- scope
- mutator
- scope

Sejatinya tidak ada yang salah dengan cara penulisan di atas, hanya saja terlihat "berserakan".

Sesuai prinsip pada bab 1 **"dekatkan yang sejenis"**, bukankah lebih bagus jika kita bisa mengelompokkan *method* sesuai jenisnya? *Relationship* berdekatan dengan *relationship*, *scope* berdekatan dengan *scope*, dan seterusnya. Dari contoh di atas, maka urutan penulisan yang lebih baik adalah:

- relationship
- relationship
- relationship
- scope
- scope
- accessor
- mutator



Nah, Trait bisa dimanfaatkan untuk **"memaksa"** programmer agar mengikuti aturan penulisan di atas. 

```php
namespace App\Models;

class User extends Model 
{
    use \App\Models\Traits\UserRelationship;
    use \App\Models\Traits\UserScope;
    use \App\Models\Traits\UserAccessor;
    use \App\Models\Traits\UserMutator;
  	
    // method lain yang tidak masuk kategori apapun
  
}
```

Selanjutnya, kita cukup membuat Trait baru untuk masing-masing jenis *method*.



```php
// app/Models/Traits/UserRelationship.php

trait UserRelationship
{
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    ...
}
```



```php
// app/Models/Traits/UserScope.php

trait UserScope
{
    public function scopeIdle($query)
    {
        return $query->whereDate('last_login', now()->subMonth(1));
    }
    ...
}
```



```php
// app/Models/Traits/UserAccessor.php

trait UserAccessor
{
    public function getFullnameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }
    ...
}
```



```php
// app/Models/Traits/UserMutator.php

trait UserMutator
{
    public function setFirstnameAttribute($name)
    {
        $this->attributes['firstname'] = ucfirst($name);
    }
    ...
}
```

Ketika nanti ada programmer baru hendak menambahkan sebuah *method* terkait *relationship*, dia akan membaca file `User.php` dan melihat sebuah petunjuk yang jelas: **"Hai, ingin menambahkan relationship baru? Ini lho sudah kami siapkan tempatnya di Trait UserRelationship"**.

Tentu tetap butuh sosialisasi dan kesadaran bersama agar aturan penulisan di atas bisa diikuti, terutama bagi programmer pemula. Tapi minimal kita sudah menyiapkan kerangka kode yang lebih mudah diikuti. 

Kebiasaan baik tidak cukup hanya dengan niat. Perlu **"fasilitas"** agar kebiasaan tersebut juga mudah dilakukan. Trait adalah salah satu fasilitas yang bisa kita manfaatkan untuk membuat organisasi kode yang lebih manusiawi.

> Di folder mana Trait dibuat dan bagaimana penamaannya bisa disesuaikan dengan karakteristik proyek. Contoh yang kami berikan mungkin cocok untuk proyek skala kecil hingga menengah. Untuk skala yang lebih besar, kita bisa sesuaikan lagi struktur foldernya, misal menjadi seperti ini:
>
>
> ```bash
> └── app
>     └── Models
>         ├── Post
>         │   ├── PostModel.php
>         │   ├── PostRelationship.php
>         │   └── PostScope.php
>         └── User
>             ├── UserAccessor.php
>             ├── UserModel.php
>             ├── UserMutator.php
>             ├── UserRelationship.php
>             └── UserScope.php
> ```
>
> Semakin sering mengerjakan proyek, maka kita akan semakin pintar **mengenali pola**. Temukan mana pola yang cocok buat kamu dan tim.

## Encapsulation Dengan Accessor

Enkapsulasi adalah salah prinsip dalam *Object Oriented Programming* dimana kita dilarang untuk mengakses secara langsung *property* sebuah *object*. Jika butuh sesuatu dari *object* tersebut, silakan panggil *method* yang sudah disediakan. 

```php
public function getFullnameAttribute()
{
    return $this->firstname.' '.$this->lastname;
}
```

*Method* `getFullnameAttribute` di atas adalah contoh enkapsulasi. 

Alih-alih memanggil kode berikut di setiap file Blade:

```php+HTML
<div>{{ $user>firstname.' '.$user->lastname }}</div>
```

Kita cukup memanggil:

```php+HTML
<div>{{ $user->fullname }}</div>
```



### Apa tujuannya?

Agar ketika ada perubahan implementasi, si pemanggil *method* (atau biasa disebut *Client Class*) tidak ikut berubah. Jadi, selama *public method* nya masih sama, semua file atau *class* yang memanggil `getFullnameAttribute()` tidak perlu tahu apakah detil implementasinya berubah atau tidak. 

Sebagai contoh, selain `firstname` dan `lastname`, sekarang seorang user juga punya atribut `middlename`. Tentu saja ini akan mengubah *logic* untuk mendapatkan `fullname`. Tapi karena kita sudah menerapkan enkapsulasi, perubahan tersebut cukup dilakukan di satu tempat saja:

```php
public function getFullnameAttribute()
{
    return $this->firstname.' '.$this->middlename.' '.$this->lastname;
}
```

Semua kelas atau file Blade yang sudah terlanjur memanggil method `fullname` tidak perlu diubah. 

Begitulah indahnya enkapsulasi.

## Mengganti Accessor Dengan Getter

Jika kamu tipikal programmer yang tidak terlalu suka dengan "magic", maka membuat method **getter** secara tradisional masih tetap boleh dilakukan.

***Getter*** adalah sebuah *method* dalam suatu kelas yang fungsinya adalah meng-enkapsulasi pemanggilan atribut.

```php
class User extends Model {
  
  public function getFullname()
  {
    $sapaan = ($this->gender === 'PRIA') ? "Bapak" : "Ibu";
    
    return $sapaan . ' ' . $this->name;
  }
  
}
```



Karena *getter* adalah sebuah *method* biasa, maka kita bisa dengan mudah membedakan mana getter dan mana atribut biasa yang diambil dari kolom basis data. Tidak ada lagi keraguan ketika membaca kode di bawah ini:

```php
{{ $user->name }} // "name" adalah sebuah kolom
{{ $user->getFullname() }} // getter dipanggil sebagai sebuah method
```



## Standardisasi Accessor Dengan Prefix

Salah satu opsi yang bisa dilakukan adalah dengan membuat aturan penamaan accessor agar mudah dipahami oleh setiap programmer yang membacanya. Salah satu aturan yang bisa diterapkan adalah dengan memberikan prefix tertentu, misalnya `present` (dari kata Presenter).

```php
class User extends Model {
  
  public function getPresentFullnameAttribute()
  {
    $sapaan = ($this->gender === 'PRIA') ? "Bapak" : "Ibu";
    
    return $sapaan . ' ' . $this->name;
  }
  
}
```



Dengan mengikuti standard seperti di atas, maka programmer yang membaca kode di bawah ini bisa langsung paham bahwa `present_fullname` bukanlah sebuah kolom, tapi sebuah **accessor**.

```php
{{ $user->name }} // Ok, saya tahu "name" adalah sebuah kolom di database
{{ $user->email }} // begitu juga email
{{ $user->present_fullname }} // ada prefix "present", berarti ini bukan kolom, easy...
```

Tentunya kamu bebas memberi aturan penamaan yang lain, yang penting **konsisten dan mudah dimengerti**.

## Bercerita Dengan Scope

Anggaplah kita akan membuat sebuah halaman `popular-post` dengan spesifikasi:

1. *Popular post* adalah artikel dengan **jumlah view lebih dari 1000** dan **jumlah comment lebih dari 100**.
1. Hanya artikel yang **diterbitkan** dalam 2 hari terakhir yang bisa dianggap sebagai *popular post*.
1. Artikel dengan jumlah **view terbanyak ditampilkan di awal**.
1. Pengunjung bisa melakukan **pencarian berdasar judul dan isi artikel**.

Tipikal kode yang akan dibuat di Controller biasanya seperti di bawah ini:

```php
class PopularPostController extends Controller
{
    public function index()
    {
        $query = Post::where('status', 'PUBLISHED')
            ->where('published_at', '>', now()->subDays(2))
            ->where('view_count', '>', 1000)
            ->where('comment_count', '>', 100);

        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('post.title', 'like', '%'.$filter.'%')
                      ->orWhere('post.content', 'like', '%'.$filter.'%');
            });
        }
        
        $posts = $query->orderByDesc('view_count')->paginate();

        return view('popular-post.index', compact('posts'));
    }
}
```

Cukup familiar kan?

Ada yang salah? 

Tidak ada.

Selama fiturnya berfungsi dan tidak ada bug, maka kode bisa dianggap benar. Tapi, selalu ada cara untuk memperbaiki kode, menyiapkan kode hari ini agar mudah dipahami oleh orang lain di masa yang akan datang.

### Bayangkan Outline-nya

Untuk contoh kasus di atas, kita bisa memanfaatkan [Query Scopes](https://laravel.com/docs/8.x/eloquent#query-scopes) untuk memindahkan *logical block* yang berdekatan (sejenis) dari Controller ke Model.

Coba kita bandingkan kode sebelumnya dengan kode di bawah ini. Menurutmu mana yang sekilas lebih mudah dipahami?

```php
class PopularPostController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->published()
            ->popular()
            ->filterByKeyword($keyword)
            ->paginate();

        return view('popular-post.index', compact('posts'));
    }
}
```

Controller yang sebelumnya berisi barisan kode prosedural yang harus kita pahami setiap barisnya, sekarang berubah hanya berisi *outline* atau **petunjuk** saja. Dari sini, kita bisa dengan mudah melanjutkan mau "membuka" *method* yang mana. Butuh melakukan perbaikan terkait pencarian, tinggal buka *method* `scopeFilterByKeyword` di model `Post`.

Keuntungan lain, kita bisa dengan mudah menerapkan fungsionalitas yang sama di tempat lain. Butuh fitur pencarian di halaman admin? Cukup panggil `->filterByKeyword()`. 

*Don't Repeat Yourself!*

### Implementasi Scope-nya

Setelah kita bisa membuat *outline*-nya , langkah berikutnya tentu saja tinggal membuat *scope* dan memindahkan *logical block* yang sebelumnya berada di Controller.

```php
class Post extends Model
{
    public function scopePopular($query)
    {
        return $query
            ->where('published_at', '>', now()->subDays(2))
            ->where('view_count', '>', 1000)
            ->where('comment_count', '>', 100)
            ->orderByDesc('view_count');
    }
    
    public function scopePublished($query)
    {
        return $query->where('status', 'PUBLISHED');
    }

    public function scopeFilterByKeyword($query, string $keyword)
    {
        // Validasi $keyword bisa kita pindahkan ke scope, sehingga Controller tidak perlu ada "if" lagi
        if (!$keyword) {
            return $query;
        }
        
        return $query->where(function ($query) use ($keyword) {
                $query->where('post.title', 'like', '%'.$filter.'%')
                      ->orWhere('post.content', 'like', '%'.$filter.'%');
            });
    }
    
}
```

Cukup mudah kan?

Kita juga bisa mengoptimasi kode lebih jauh lagi, dengan memindahkan *method* terkait *scope* ke Trait tersendiri. Tapi hal tersebut opsional saja. Sesuaikan dengan kompleksitas aplikasi yang sedang kita kembangkan. Jangan sampai melakukan ***premature optimization***.

### Referensi

https://laravel.com/docs/8.x/eloquent#query-scopes



## Pegawais? Penggunas? Saatnya "Break The Rules!"

Kita tahu bahwa satu Eloquent Model merepresentasikan satu buah tabel di *database*. Kita juga sudah paham dengan konvensi penamaan tabel yang merupakan bentuk jamak dari nama model. 

| Model (singular) | Nama Tabel (plural) |
| ---------------- | ------------------- |
| User             | users               |
| Book             | books               |
| Box              | boxes               |
| Person           | people (hmm)        |
| Ox               | oxen (???)          |

Dalam bahasa Inggris, perubahan dari singular menjadi bentuk plural dibagi menjadi dua: 

1. Yang beraturan, biasanya ditambahi `s` atau `es` dibelakang kata.
2. Yang tidak beraturan, sesuai dua contoh terakhir di atas.

Itulah kenapa sering dijumpai nama tabel yang aneh dibaca seperti **penggunas** dan **pegawais**. Kita membuat aplikasi dengan *domain* Indonesia, tapi mengikuti konvensi bahasa Inggris. Jadinya kan aneh.

Konvensi nama Model dan nama tabel dibuat untuk memudahkan koding. Namun, ketika konvensi tersebut terasa aneh, maka kita boleh melanggarnya. **Break The Rules!**.

Cukup *override* `$table` dan kita bisa menentukan nama tabel dengan bebas, tentunya nama yang lebih manusiawi.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
}
```

Semoga setelah membaca ini, kita dijauhkan dari memberi nama tabel yang tidak manusiawi.

> Beberapa orang menyarankan untuk *strict* memakai bahasa Inggris ketika memberi nama kelas, variabel, ataupun tabel, meskipun aplikasi yang kita buat untuk kebutuhan orang Indonesia. Menurut opini saya, penamaan yang paling tepat adalah yang konsisten dan sesuai dengan istilah yang sering digunakan sehari-hari oleh pengguna.
>
> Sebagai contoh, jika klien dari pemerintahan sudah terbiasa dengan istilah **pegawai**, maka akan lebih bijak jika istilah tersebut konsisten dipakai mulai dari dokumen analisis, penamaan dalam koding, hingga desain *database*. Tidak perlu memaksakan menjadi ***employee***.

### Referensi

- https://laravel.com/docs/master/eloquent#table-names

## Pasti Aman Dengan withDefault()

Seberapa sering kamu mendapatkan error **Trying to get property of non-object**?

Contoh paling klasik penyebab error tersebut skenario **User *has one* Profile**, dengan contoh *relationship* seperti di bawah ini:

```php
class User extends Model
{
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
```

Pada kondisi ideal, memanggil `$user->profile->bio` untuk menampilkan bio dari seorang *user* sepertinya aman-aman saja. Kita kan sudah mendefinisikan *relationship*-nya.

Namun apa jadinya jika ternyata ada sebuah data di tabel `users` yang tidak ada relasinya di tabel `profiles`? Ada banyak hal yang bisa menjadi penyebabnya:

1. Menyederhanakan proses registrasi, sehingga User tidak diminta untuk mengisi profile dari awal.
1. *Legacy data* dari aplikasi lama dimana waktu itu belum ada isian profil User secara lengkap.
1. Kesalahan koding, misalnya tidak menerapkan *database transaction*, sehingga menyebabkan rusaknya integritas data.

Pada kasus-kasus di atas, memanggil `$user->profile->bio` jelas berpotensi menimbulkan error **Trying to get property of non-object**.

Ketika kita tidak punya kuasa untuk memastikan **integritas data** di level basis data, maka mau tidak mau kita harus menerapkan kembali *defensive programming* di level kode. Dalam kasus ini, `withDefault` merupakan pilihan yang tepat.

```php
class User extends Model
{
    public function profile()
    {
        return $this->hasOne(Profile::class)->withDefault();
    }
}
```

Dengan penambahan `withDefault` seperti di atas, maka memanggil `$user->profile` dijamin tidak akan *return* `null`. Jika tidak ada data terkait User tersebut di tabel `profiles`, `$user->profile` akan tetap mengembalikan *object* `Profile`, hanya saja semua atributnya bernilai `null`.

Jika ingin mengeset *default value* dari suatu atribut, kita bisa menambahkan:

```php
class User extends Model
{
    public function profile()
    {
        return $this->hasOne(Profile::class)->withDefault(['bio' => '-masih kosong-']);
    }
}
```

*Relationship* yang bisa ditambahkan `withDefault` adalah:

1. belongsTo
1. hasOne
1. hasOneThrough
1. morphOne

Dokumentasi lengkap terkait `withDefault` bisa dibaca di https://laravel.com/docs/8.x/eloquent-relationships#default-models.

### PHP 8.0: Null-Safe Operator

Sejak PHP 8.0, kita bisa menghindari error "sejuta umat" tersebut dengan menerapkan operator `?->`:

```php
$user->profile?->bio;
```

Kode di atas tidak akan menghasilkan *error* meskipun `$user->profile` bernilai `null`. 

> Ada banyak fitur baru sejak PHP 8.0 yang membuatnya terasa lebih modern. Kita akan membahasnya lebih mendalam di buku tersendiri :) 



> Null Object merupakan salah satu *design pattern* yang bisa dimanfaatkan untuk menghindari pengecekan `if($foo !== null)` di banyak tempat.

### Referensi

- https://designpatternsphp.readthedocs.io/en/latest/Behavioral/NullObject/README.html
- https://docs.php.earth/php/ref/oop/design-patterns/null-object/
- https://php.watch/versions/8.0/null-safe-operator



## Pasti Konsisten Dengan DB Transaction

- contoh kasus: Update profile page

## Intermeso: Fat Model

