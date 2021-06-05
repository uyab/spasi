# Model

## Tetap Langsing Dengan Trait

## Tetap Rapi Dengan Trait

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



Karena *getter* adalah sebuah *method* biasa, maka kita bisa dengan mudah membedakan mana getter dan mana atribut biasa (diambil dari kolom database). Tidak ada lagi keraguan ketika membaca kode di bawah ini.

```php
{{ $user->name }} // kolom dipanggil sebagai atribut biasa
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

