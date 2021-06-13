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

Bukankah lebih bagus jika kita bisa mengelompokkan *method* yang sejenis? *Relationship* berdekatan dengan *relationship*, *scope* berdekatan dengan *scope*, dan seterusnya. Dari contoh di atas, maka urutan penulisan yang lebih baik adalah:

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

###### app/Models/Traits/UserRelationship.php

```php
trait UserRelationship
{
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    ...
}
```

###### app/Models/Traits/UserScope.php

```php
trait UserScope
{
    public function scopeIdle($query)
    {
        return $query->whereDate('last_login', now()->subMonth(1));
    }
    ...
}
```

###### app/Models/Traits/UserAccessor

```php
trait UserAccessor
{
    public function getFullnameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }
    ...
}
```

###### app/Models/Traits/UserMutator.php

```php
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

Tentunya kamu bebas memberi aturan penamaan yang lain, yang penting konsisten dan mudah dimengerti.

## Berdamai Dengan Active Record

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

## Bercerita Dengan Scope

## Pasti Aman Dengan withDefault()

## Intermeso: Fat Model

