# lib-mail

Adalah module penengah untuk mengirimkan email. Modul ini tidak bisa mengirimkan
email sendiri, dibutuhkan module sender yang akan menangani proses pengiriman
email.

## Instalasi

Jalankan perintah di bawah di folder aplikasi:

```
mim app install lib-mail
```

## Konfigurasi

Tambahkan konfigurasi seperti di bawah pada konfigurasi modul untuk mendaftarkan
email sender.

```php
return [
	'libMail' => [
		'handler' => 'Namespace\\Library\\Sender'
	]
];
```

## Sender

Buatkan sebuat class yang mengimplementasikan interface `LibMail\Iface\Sender` yang
bertugas mengirimkan email. Class tersebut harus memiliki method sebagai berikut:

### static function send(array $options): bool;

Parameter `options` akan dikirim dalam bentuk final sebagai berikut:

```php
$options = [
	'to' => [
		'name'  => 'Receiver Name',
		'email' => 'receiver@mail',
		'cc'    => [ // optional
			[
				'name'  => 'CC Receiver Name',
				'email' => 'cc.receiver@mail'
			],
			// ...
		],
		'bcc'   => [ // optional
			[
				'name'  => 'BCC Receiver Name',
				'email' => 'bcc.receiver@mail'
			],
			// ...
		]
	],
	'subject' => 'Email Subject',
	'attachment' => [ // optional
		[
			'file' => 'absolute/path/to/file.txt',
			'name' => 'file.txt'
		]
		// ...
	],
	'text' => 'Standar text content email without HTML',
	'html' => '<strong>Standar html content email with HTML</strong>'
];
```

### static function lastError(): ?string;

## Penggunaan

Module ini mendaftarkan satu library dengan nama `LibMail\Library\Mail` yang bisa
digunakan untuk mengirim email sebagai berikut:

```php
use LibMail\Library\Mail;

$options = [
	'to' => [
		[
			'name'  => 'Receiver Name',
			'email' => 'receiver@mail',
			'cc'    => [ // optional
				[
					'name'  => 'CC Receiver Name',
					'email' => 'cc.receiver@mail'
				],
				// ...
			],
			'bcc'   => [ // optional
				[
					'name'  => 'BCC Receiver Name',
					'email' => 'bcc.receiver@mail'
				],
				// ...
			]
		],
		// ...
	],
	'subject' => 'Email Subject',
	'attachment' => [ // optional
		[
			'file' => 'absolute/path/to/file.txt',
			'name' => 'file.txt'
		]
		// ...
	],
	'text' => 'Standar text content email without HTML',
	'view' => [ // optional
		'path'   => 'path/to/view/file',
		'params' => [ /* ... */ ]
	]
];

$result = Mail::send($options);
if(!$result)
	print_r( Mail::lastError() );
```

Keterangan dari masing-masing nilai opsi di atas adalah sebagai berikut:

1. `to`  Tujuan pengiriman email, boleh lebih dari satu penerima dimana masing-masing
penerima memiliki properti `name` dan `email`. Nilai ini juga akan diteruskan ke view
jika ada dengan nama variabel `to`.
	- `cc`  Sama seperti `to`, tapi akan dikirim dengan penerimaan sebagai `cc`.
	- `bcc` Sama seperti `to`, tapi akan dikirim dengan penerimaan sebagai `bcc`.
1. `subject`  Subject email, nilai ini menerima string `(:to.email)` atau `(:to.name)` yang
akan diganti dengan properti akun peneriman `to.email` atau `to.name`.
1. `attachment`  Adalah attachment file yang akan dikirim bersamaan dengan email.
Masing-masing file harus memiliki properti `file` yang adalah absolute path ke file
tersebut, dan `name` yang adalah nama file tersebut.
1. `text`  Berisi konten email tanpa HTML untuk dukungan email reader yang belum mendukung
html. Nilai ini juga akan diset sebagai konten HTML jika properti `view` tidak diset.
1. `view`  Berisi informasi view yang akan di-render untuk menggenerasi konten html email.
Properti ini memiliki dua sub-properti yaitu:
	- `path` Path nama view yang akan digunakan yang ada di folder `./theme/mail`.
	- `params` Parameter key-value pair yang akan digunakan sebagai parameter variabel
	untuk menggenerasi view html.