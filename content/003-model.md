# Model

## Tetap Langsing Dengan Trait

## Tetap Rapi Dengan Trait

## Encapsulation Dengan Accessor

## Mengganti Accessor Dengan Getter

## Standardisasi Accessor Dengan Prefix

## Berdamai Dengan Active Record

## Pegawais? Penggunas? Saatnya "Break The Rules!"

Kita tahu bahwa satu Eloquent Model merepresentasikan satu buah tabel di *database*. Kita juga sudah paham dengan konvensi penamaan tabel yang merupakan bentuk jamak dari nama model. 

| No   | Model (singular) | Nama Tabel (plural) |
| ---- | ---------------- | ------------------- |
| 1    | User             | users               |
| 2    | Book             | books               |
| 3    | Box              | boxes               |
| 4    | Person           | people (hmm)        |
| 5    | Ox               | oxen (???)          |

Dalam bahasa Inggris, perubahan dari singular menjadi bentuk plural dibagi menjadi dua: 

1. Yang beraturan, biasanya ditambahi `s` atau `es` dibelakang kata.
2. Yang tidak beraturan, sesuai contoh 4 dan 5 di atas.

Itulah kenapa sering dijumpai nama tabel yang aneh dibaca seperti **penggunas** dan **pegawais**. Kita membuat aplikasi dengan *domain* Indonesia, tapi mengikuti konvensi bahasa Inggris. Jadinya kan aneh.

Konvensi nama Model dan nama tabel dibuat untuk memudahkan koding. Namun, ketika konvensi tersebut terasa aneh, maka kita boleh melanggarnya. **Break The Rules!**.

Cukup *override* `$table` dan isi dengan nama tabel yang lebih manusiawi dan masuk akal.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
}
```

Semoga setelah membaca ini, kita dijauhkan dari memberi nama tabel yang tidak manusiawi dan tidak konsisten.

> Beberapa orang menyarankan untuk *strict* memakai bahasa Inggris ketika memberi nama kelas, variabel, ataupun tabel, meskipun aplikasi yang kita buat untuk kebutuhan orang Indonesia. Menurut opini saya, penamaan yang paling tepat adalah yang konsisten dan sesuai dengan istilah yang sering digunakan sehari-hari oleh pengguna.
>
> Sebagai contoh, jika klien dari pemerintahan sudah terbiasa dengan istilah **pegawai**, maka akan lebih bijak jika istilah tersebut konsisten dipakai mulai dari dokumen analisis, penamaan dalam koding, hingga desain *database*. Tidak perlu memaksakan menjadi ***employee***.

### Referensi

- https://laravel.com/docs/master/eloquent#table-names

## Bercerita Dengan Scope

## Pasti Aman Dengan withDefault()

## Intermeso: Fat Model

