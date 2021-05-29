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

## Buat Outline (Daftar Isi)
Seberapa sering kamu corat-coret (outlining) sebelum koding? Outline merupakan salah satu metode agar kodingan lebih runut dan terstruktur.

Sebagai contoh, kita akan membuat sebuah fungsi untuk menyimpan informasi buku dan gambar *cover*-nya. Maka mulailah dengan menuliskan langkah apa saja yang diperlukan untuk menyelesaikan fungsi tersebut.

```php
public function store(Request $request)
{
  // 1. validasi form
  // 2. simpan file gambar cover
  // 3. simpan data buku ke DB
  // 4. redirect dan tampilkan pesan sukses
}
```

Setelah *outline* dirasa cukup, kita tinggal menuliskan kode untuk setiap langkah. **Kerjakan dulu langkah yang paling gampang**. Jika ada kesulitan di suatu langkah, cukup tuliskan `//TODO` dan gunakan ***dummy variable*** dulu.

```php
// Kode diambil dari https://github.com/febrihidayan/laravel-blog dengan modifikasi.

public function store(Request $request)
{
  // 1. validasi form
    $this->validate($request, [
    'judul' => 'required|string|max:255',
    'isbn' => 'required|string'
  ]);
  
  //TODO 2. simpan file gambar cover
  $cover = "-";
  
  // 3. simpan data buku ke DB
  Buku::create([
    'judul' => $request->get('judul'),
    'isbn' => $request->get('isbn'),
    'pengarang' => $request->get('pengarang'),
    'penerbit' => $request->get('penerbit'),
    'tahun_terbit' => $request->get('tahun_terbit'),
    'jumlah_buku' => $request->get('jumlah_buku'),
    'deskripsi' => $request->get('deskripsi'),
    'lokasi' => $request->get('lokasi'),
    'cover' => $cover
  ]);
  
  // 4. redirect dan tampilkan pesan sukses
  alert()->success('Berhasil.','Data telah ditambahkan!');
  
  return redirect()->route('buku.index');  
}
```

Pada contoh di atas, menyimpan gambar hasil upload dirasa cukup sulit untuk dilakukan. Maka kita bisa mengabaikannya dulu, namun tidak sampai menjadi *blocking* karena aplikasi tetep bisa dites dan *flow* tetap berjalan dengan normal.

## Bercerita Dengan Protected Method

Baris kode adalah buah pikir programmer yang menulisnya. Sama seperti cerpen yang dihasilkan seorang penulis. Sama seperti pidato atau video motivasi yang dihasilkan seorang _public speaker_.

Pemilihan kata, pemenggalan kalimat, dan intonasi menjadi penting agar pesan tersampaikan.

Dari ketiga aspek M-V-C,  **biasanya** Controller menjadi tempat yang paling sering dikunjungi (untuk dibaca atau ditulis). Oleh sebab itu menjadi penting untuk membuat sebuah Controller yang bisa "bercerita", agar pengunjung (programmer setelahmu, atau kamu sendiri tiga bulan kemudian) tidak tersesat.



Melanjutkan contoh sebelumnya, jika ada satu ***logical block*** yang dirasa cukup kompleks, kita bisa memindahkannya ke fungsi tersendiri. Dalam konsep OOP, kita bisa membuat sebuah ***protected method***.


```php
// Kode diambil dari https://github.com/febrihidayan/laravel-blog dengan modifikasi.

public function store(Request $request)
{
  // 1. validasi form
    $this->validate($request, [
    'judul' => 'required|string|max:255',
    'isbn' => 'required|string'
  ]);
  
  //TODO 2. simpan file gambar cover
  $cover = $this->uploadCover($request);

  // 3. simpan data buku ke DB
  Buku::create([
    'judul' => $request->get('judul'),
    'isbn' => $request->get('isbn'),
    'pengarang' => $request->get('pengarang'),
    'penerbit' => $request->get('penerbit'),
    'tahun_terbit' => $request->get('tahun_terbit'),
    'jumlah_buku' => $request->get('jumlah_buku'),
    'deskripsi' => $request->get('deskripsi'),
    'lokasi' => $request->get('lokasi'),
    'cover' => $cover
  ]);

  // 4. redirect dan tampilkan pesan sukses
  alert()->success('Berhasil.','Data telah ditambahkan!');

  return redirect()->route('buku.index');

}

protected function uploadCover($request)
{
  $cover = null;

  if($request->file('cover')) {
    $file = $request->file('cover');
    $dt = Carbon::now();
    $acak  = $file->getClientOriginalExtension();
    $fileName = rand(11111,99999).'-'.$dt->format('Y-m-d-H-i-s').'.'.$acak; 
    $request->file('cover')->move("images/buku", $fileName);
    $cover = $fileName;
  }

  return $cover;
}
```



Jika proses validasi form dan proses menyimpan ke database dirasa cukup kompleks, kita bisa melakukan hal yang sama untuk kedua ***logical block*** tersebut.

```php
public function store(Request $request)
{
  $this->validateBuku($request);  

  $cover = $this->uploadCover($request);
  
  $buku = $this->storeBuku($request, $cover);

  alert()->success('Berhasil.','Data telah ditambahkan!');

  return redirect()->route('buku.index');
}
```

Apakah menurutmu kode di atas lebih mudah dipahami? Untuk Controller yang sederhana bisa jadi tidak terlalu terlihat bedanya. Tapi percayalah bahwa kebiasaan ini akan sangat bermanfaat ketika kompleksitas kode yang kamu buat sudah semakin meningkat.



Selamat bercerita!

## Reusable Dengan Trait

protected method hanya dikenali dalam Class yang sama
jika butuh lintas Class, manfaatkan Trait

## Pasti Sama Dengan Base Class
Jika butuh pengakuan (identitas), terapkan Inheritance.

## Maksimal Tujuh Dengan Resource Controller

Bagaimana kalau saya bilang, seberapapun kompleksnya aplikasi yang kamu bangun, jumlah Action dalam suatu Controller selalu bisa dibikin agar **tidak pernah lebih dari tujuh**.

Tujuh adalah jumlah aksi maksimal yang bisa kita lakukan terhadap suatu _resource_, paling tidak demikianlah [Laravel mengajarkan kita](https://laravel.com/docs/master/controllers#resource-controllers).

![](http://spasi.test/assets/img/resource-controller.png)

Terlebih lagi jika aplikasi yang sedang dikembangkan bertipikal CRUD, aturan **masksimal tujuh** harusnya bisa dengan mudah diterapkan. Kita tidak perlu membuat Custom Action di Controller. 

### Apa Itu Custom Action?
Custom Action adalah ketika kamu mendefinisikan route dan method baru di Controller, di luar tujuh standard.

```php
// routes/web.php
Route::resource('users', UserController::class);
Route::get('/users/downloadPdf', [UserController::class, 'downloadPdf']);

// UserController.php
class UserController extends Controller {
  public function index(){...}
  public function show(){...}
  public function create(){...}
  public function store(){...}
  public function edit(){...}
  public function update(){...}
  public function delete(){...}

  public function downloadPdf()
  {
    // generate PDF
  }
  
}
```
Pada contoh di atas, `downloadPdf()` merupakan Custom Action.

### Apa Itu Resource Controller?
**Resource Controller** adalah sebuah konsep untuk menunjukkan hubungan antara data dan aksi apa saja yang bisa dilakukan terhadap data tersebut. _Resource_ biasanya mengacu ke sebuah tabel _database_, gabungan beberapa tabel (join), sub tabel (tabel dengan kondisi tertentu), kolom (atribut), atau entitas lain sesuai kebutuhan aplikasi.

| Resource (Data) | Controller              | Contoh Aksi                                                                                             |
|-----------------|-------------------------| ------------------------------------------------------------------------------------------------------- |
| Satu tabel      | PostController          | index (tampilkan semua post) <br> store (menyimpan Post baru) <br> destroy (hapus permanen sebuah Post) |
| Banyak tabel    | StatisticController     | index                                                                                                   |
| Sub tabel       | PublishedPostController | store (publish post) <br> destroy (unpublish post)                                                      |
| Kolom tertentu  | PasswordController      | edit <br> update <br> ~~destroy~~ (password tidak bisa didelete)                                        |
| Entitas lain    | DbBackupController      | index (tampilkan semua backup)<br> store (menambah backup baru)<br> destroy (hapus salah satu backup)   |

### Berpikir Resource
Sampai di sini kamu sudah mengenal apa itu Custom Action dan apa itu Resource Controller, dan _goal_ yang ingin dicapai adalah bagaimana menghilangkan Custom Action agar semua Controller bisa _**strict**_ hanya memakai **tujuh kata**.

### Studi Kasus
Mari kita latihan membuat Resource Controller dari beberapa contoh kasus yang sering kita temui. 

#### Follow Unfollow
```
// BAD
UserController@follow
UserController@unfollow

// GOOD

```



Kembali ke contoh `downloadPdf()`, ada solusi lain yang lebih tepat, yaitu dengan membuat _dedicated_ **Single Action Controller**.


##  Single Action Controller Untuk "Sisanya"
Kata kerja (action)
contoh:
- redirect setelah login
- download pdf
- logout

```php
// routes/web.php
Route::post('/users/pdf', User/DownloadPdf::class);

// Controller/User/DownloadPdf.php
class DownloadPdf extends Controller {
  public function __invoke()
  {
    // generate PDF
  }
  
}
```


## Dimana Pengecekan Hak Akses?

## FormRequest Yang Terabaikan

## Kapan Perlu Membuat Service Class?