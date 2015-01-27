<?php

/**
 * Created by Atinasoft.
 * Project: w3c-sql-editor-with-turkish-database
 * User: ErenHatirnaz
 * Date: 02.01.2015
 * Time: 15:46
 * File: random-database-generator.php
 */
define('CURRENT_DIRECTORY', dirname(__FILE__));
class RandomDatabaseGenerator {
    private $db;
    private $kisiler;

    function __construct() {
        $this->db = new PDO("sqlite:database.db");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->kisiler = file(CURRENT_DIRECTORY . DIRECTORY_SEPARATOR . 'turkce-ad-soyad-listesi.txt'); // Derleme Hüseyin Demirtaş'a aittir. Kaynak: http://huseyindemirtas.net/kullanici-adi-listesi-ad-soyad-listesi/
    }

    public function generateDatabase() {
        echo "+ Veritabanı oluşturma işlemi başladı.\n\n";
        echo "+ Tablolar oluşturuluyor: \n";

        $this->db->exec("CREATE TABLE Calisanlar (
            CalisanID  INTEGER PRIMARY KEY AUTOINCREMENT,
            Ad         VARCHAR NOT NULL,
            Soyad      VARCHAR NOT NULL,
            DogumTarih DATE    NOT NULL,
            Resim      VARCHAR,
            Notlar     TEXT
        );");
        echo "\t [+] Calisanlar tablosu oluşturuldu.\n";

        $this->db->exec("CREATE TABLE KargoFirmalari (
            KFirmaID  INTEGER PRIMARY KEY AUTOINCREMENT,
            KFirmaAdi VARCHAR NOT NULL,
            Telefon   VARCHAR
        );");
        echo "\t [+] KargoFirmalari tablosu oluşturuldu.\n";

        $this->db->exec("CREATE TABLE Kategoriler (
            KategoriID  INTEGER PRIMARY KEY AUTOINCREMENT,
            KategoriAdi VARCHAR NOT NULL,
            Aciklama    TEXT
        );");
        echo "\t [+] Kategoriler tablosu oluşturuldu.\n";

        $this->db->exec("CREATE TABLE Musteriler (
            MusteriID  INTEGER PRIMARY KEY AUTOINCREMENT,
            MusteriAdi VARCHAR NOT NULL,
            Adres      TEXT    NOT NULL,
            Sehir      VARCHAR NOT NULL,
            PostaKodu  INTEGER NOT NULL,
            Ulke       VARCHAR NOT NULL
        );");
        echo "\t [+] Musteriler tablosu oluşturuldu.\n";

        $this->db->exec("CREATE TABLE Saticilar (
            SaticiID    INTEGER PRIMARY KEY AUTOINCREMENT,
            SaticiAdi   VARCHAR NOT NULL,
            İletisimAdi VARCHAR NOT NULL,
            Adres       TEXT    NOT NULL,
            Sehir       VARCHAR NOT NULL,
            PostaKodu   INTEGER NOT NULL,
            Ulke        VARCHAR NOT NULL,
            Telefon     VARCHAR NOT NULL UNIQUE
        );");
        echo "\t [+] Saticilar tablosu oluşturuldu.\n";

        $this->db->exec("CREATE TABLE Urunler (
            UrunID     INTEGER PRIMARY KEY AUTOINCREMENT,
            UrunAdi    VARCHAR NOT NULL,
            SaticiID   INT     NOT NULL REFERENCES Saticilar ( SaticiID ),
            KategoriID INTEGER NOT NULL REFERENCES Kategoriler ( KategoriID ),
            Stok       VARCHAR NOT NULL,
            Fiyat      REAL    NOT NULL
        );");
        echo "\t [+] Urunler tablosu oluşturuldu.\n";

        $this->db->exec("CREATE TABLE Siparisler (
            SiparisID     INTEGER PRIMARY KEY AUTOINCREMENT,
            MusteriID     INTEGER NOT NULL REFERENCES Musteriler ( MusteriID ) ON DELETE CASCADE,
            CalisanID     INTEGER NOT NULL REFERENCES Calisanlar ( CalisanID ) ON DELETE CASCADE,
            SiparisTarihi DATE    NOT NULL,
            KargoFirmaID  INTEGER NOT NULL REFERENCES KargoFirmalari ( KFirmaID )
        );");
        echo "\t [+] Siparisler tablosu oluşturuldu.\n";

        $this->db->exec("CREATE TABLE SiparisDetaylari (
            SiparisDetayID INTEGER PRIMARY KEY AUTOINCREMENT,
            SiparisID      INTEGER NOT NULL REFERENCES Siparisler ( SiparisID ) ON DELETE CASCADE,
            UrunID         INTEGER NOT NULL REFERENCES Urunler ( UrunID ) ON DELETE CASCADE,
            Adet           INTEGER NOT NULL
        );");
        echo "\t [+] SiparisDetaylari tablosu oluşturuldu.\n";

        echo "+ Tüm tablolar başarılı bir şekilde oluşturuldu.\n";
        $iller = array( 'Adana', 'Adıyaman', 'Afyon', 'Ağrı', 'Aksaray', 'Amasya', 'Ankara', 'Antalya', 'Ardahan', 'Artvin', 'Aydın', 'Balıkesir', 'Bartın', 'Batman', 'Bayburt', 'Bilecik', 'Bingöl', 'Bitlis', 'Bolu', 'Burdur', 'Bursa', 'Çanakkale', 'Çankırı', 'Çorum', 'Denizli', 'Diyarbakır', 'Düzce', 'Edirne', 'Elazığ', 'Erzincan', 'Erzurum', 'Eskişehir', 'Gaziantep', 'Giresun', 'Gümüşhane', 'Hakkari', 'Hatay', 'Iğdır', 'Isparta', 'İçel', 'İstanbul', 'İzmir', 'Kahramanmaraş', 'Karabük', 'Karaman', 'Kars', 'Kastamonu', 'Kayseri', 'Kırıkkale', 'Kırklareli', 'Kırşehir', 'Kilis', 'Kocaeli', 'Konya', 'Kütahya', 'Malatya', 'Manisa', 'Mardin', 'Muğla', 'Muş', 'Nevşehir', 'Niğde', 'Ordu', 'Osmaniye', 'Rize', 'Sakarya', 'Samsun', 'Siirt', 'Sinop', 'Sivas', 'Şanlıurfa', 'Şırnak', 'Tekirdağ', 'Tokat', 'Trabzon', 'Tunceli', 'Uşak', 'Van', 'Yalova', 'Yozgat', 'Zonguldak' );

        $eklenecekMusteriSayisi = rand(100, 250);
        $eklenecekCalisanSayisi = rand(25, 150);

        echo "+ Veritabanınıza " . $eklenecekMusteriSayisi . " adet müşteri ve " . $eklenecekCalisanSayisi . " adet çalışan eklenecek.\n";

        echo "+ Veritabanınıza müşteriler ekleniyor...\n";
        for ($i = 0; $i < $eklenecekMusteriSayisi; $i++) {
            $adSoyad = $this->kisiler[rand(0, sizeof($this->kisiler) - 1)];
            $sehir = $iller[rand(0, sizeof($iller) - 1)];
            $postaKodu = rand(10000, 81999);

            $musteriEkle = $this->db->prepare("INSERT INTO Musteriler(MusteriAdi, Adres, Sehir, PostaKodu, Ulke) VALUES (:musteriAdi, :adres, :sehir, :postaKodu, 'Türkiye')");
            $musteriEkle->execute(array(
                ':musteriAdi' => $adSoyad,
                ':adres'      => $sehir,
                ':sehir'      => $sehir,
                ':postaKodu'  => $postaKodu
            ));

            unset($adSoyad);
        }
        echo "+ Musteriler tablosuna " . $eklenecekMusteriSayisi . " adet müşteri başarılı bir şekilde eklendi.\n";

        echo "+ Veritabanınıza çalışanlar ekleniyor...\n";
        for ($i = 0; $i < $eklenecekCalisanSayisi; $i++) {
            $adSoyad = explode(" ", $this->kisiler[rand(0, sizeof($this->kisiler) - 1)]);
            $ad = $adSoyad[0];
            $soyad = $adSoyad[1];
            $dogumTarih = rand(1, 28) . "/" . rand(1, 12) . '/' . rand(1960, 1995);
            $resim = "Calisan" . $i . ".jpg";

            $calisanEkle = $this->db->prepare("INSERT INTO Calisanlar(Ad, Soyad, DogumTarih, Resim) VALUES (:ad, :soyad, :dogumTarih, :resim)");
            $calisanEkle->execute(array(
                ':ad'         => $ad,
                ':soyad'      => $soyad,
                ':dogumTarih' => $dogumTarih,
                ':resim'      => $resim
            ));
        }
        unset($kisiler);

        echo "+ Calisanlar tablosuna " . $eklenecekCalisanSayisi . " adet müşteri başarılı bir şekilde eklendi.\n";

        echo "+ KargoFirmalari tablosuna 4 adet Kargo Firması ekleniyor...\n";
        $kargoFirmalari = array(
            array( 'FirmaAdi' => "Aras Kargo", 'Telefon' => "4442552" ),
            array( 'FirmaAdi' => "Yurtiçi Kargo", 'Telefon' => "4449999" ),
            array( 'FirmaAdi' => "MGN Kargo", 'Telefon' => "4440606" ),
            array( 'FirmaAdi' => "UPS Kargo", 'Telefon' => "4440066" )
        );
        shuffle($kargoFirmalari);
        foreach ($kargoFirmalari as $kargoFirmasi) {
            $kargoFirmasiEkle = $this->db->prepare("INSERT INTO KargoFirmalari(KFirmaAdi, Telefon) VALUES (:urunAdi, :telefon)");
            $kargoFirmasiEkle->execute(array(
                ':urunAdi' => $kargoFirmasi['FirmaAdi'],
                ':telefon' => $kargoFirmasi['Telefon']
            ));
        }
        echo "+ Kargo Firmaları başarılı bir şekilde eklendi.\n";

        echo "+ Saticilar tablosuna 13 adet Satıcı Firma ekleniyor...\n";
        $saticilar = array(
            array( 'SaticiAdi' => "İdefix", 'İletisimAdi' => "Doğan Müzik Kitap Mağazacılık ve Pazarlama AŞ.", 'Adres' => "Altunizade Mah. Kısıklı Cad. No:47 Üsküdar / İstanbul - TÜRKİYE", 'Sehir' => "İstanbul", 'PostaKodu' => "34662", 'Ulke' => "Türkiye", 'Telefon' => "08502660606" ),
            array( 'SaticiAdi' => "Hepsiburada", 'İletisimAdi' => "D-Market Elektronik Hizmetler ve Tic. A. Ş. ", 'Adres' => "Kuştepe Mah. Mecidiyeköy Yolu Cad. Trump Towers Kule 2 Kat:2 No:12 34387 Şişli/İstanbul ", 'Sehir' => "İstanbul", 'PostaKodu' => "34381", 'Ulke' => "Türkiye", 'Telefon' => "08502524000" ),
            array( 'SaticiAdi' => "Kitapyurdu", 'İletisimAdi' => "Repar Tanıtım İletişim Matbaacılık Ltd. Şti.", 'Adres' => "Örnektepe mh. Pazaraltı sk. No:96 Sütlüce-İstanbul", 'Sehir' => "İstanbul", 'PostaKodu' => "34445", 'Ulke' => "Türkiye", 'Telefon' => "02125198720" ),
            array( 'SaticiAdi' => "Kaft", 'İletisimAdi' => "KAFT Tasarım Tekstil San. ve Tic. A.Ş.", 'Adres' => " Şehit Ahmet Sk. Mecidiyeköy İş Merkezi No:4/94 34387 Şişli/İstanbul", 'Sehir' => "İstanbul", 'PostaKodu' => "34381", 'Ulke' => "Türkiye", 'Telefon' => "02122673634" ),
            array( 'SaticiAdi' => "Robotistan", 'İletisimAdi' => "Bomec Robot Teknolojileri San. Tic. Ltd. Şti.", 'Adres' => "Perpa Ticaret Merkezi B Blok Kat:8 No:1101 (Halk Bankası Yanı)", 'Sehir' => "İstanbul", 'PostaKodu' => "34381", 'Ulke' => "Türkiye", 'Telefon' => "08507660425" ),
            array( 'SaticiAdi' => "Compel", 'İletisimAdi' => "Compel Ltd. Şti.", 'Adres' => "Duatepe Mahallesi, Ergenekon Caddesi No:113/A Şişli İstanbul", 'Sehir' => "İstanbul", 'PostaKodu' => "34381", 'Ulke' => "Türkiye", 'Telefon' => "02122243201" ),
            array( 'SaticiAdi' => "Gold Computer", 'İletisimAdi' => "Gold Teknoloji Marketleri Sanayi ve Ticaret A.Ş.", 'Adres' => "Yukarı Dudullu Mahallesi Organize Sanayi Bölgesi 1.Cadde No: 13/7 Ümraniye - İstanbul", 'Sehir' => "İstanbul", 'PostaKodu' => "34775", 'Ulke' => "Türkiye", 'Telefon' => "02165547575" ),
            array( 'SaticiAdi' => "Dikeyeksen", 'İletisimAdi' => "Dikeyeksen Yayın Dağıtım Yazılım ve Eğitim Hizm. San. ve Tic. LTD. ŞTİ.", 'Adres' => "Mehmet Akif Mh. Elalmış Cd. Tarık Buğra Sk. NO:23/A Ümraniye/İstanbul", 'Sehir' => "İstanbul", 'PostaKodu' => "34775", 'Ulke' => "Türkiye", 'Telefon' => "02164201980" ),
            array( 'SaticiAdi' => "Teknosa", 'İletisimAdi' => "TeknoSA İç ve Dış Ticaret A.Ş.", 'Adres' => "Teknosa Plaza Batman Sokak No: 18 Sahrayıcedit, İstanbul", 'Sehir' => "İstanbul", 'PostaKodu' => "34734", 'Ulke' => "Türkiye", 'Telefon' => "02164683636" ),
            array( 'SaticiAdi' => "Vatan Bilgisayar", 'İletisimAdi' => "Vatan Bilgisayar San. ve Tic. A.Ş.", 'Adres' => "Cevizlibağ Merkez Efendi Mah. Tercüman Sitesi Çarşı Binası Seyitnizam, Zeytinburnu, İstanbul", 'Sehir' => "İstanbul", 'PostaKodu' => "34010", 'Ulke' => "Türkiye", 'Telefon' => "02124145900" ),
            array( 'SaticiAdi' => "Gittigidiyor", 'İletisimAdi' => "Gitti Gidiyor Bilgi Teknolojileri San. ve Tic. A.Ş.", 'Adres' => "My Office İş Mrkz., Çiğdem Sk., No: 1/14, Barbaros Mah., Ataşehir, İstanbul", 'Sehir' => "İstanbul", 'PostaKodu' => "34746", 'Ulke' => "Türkiye", 'Telefon' => "02166870299" ),
            array( 'SaticiAdi' => "Webdenal", 'İletisimAdi' => "FGG Mak. Danş. Tanıtım Ve Bilişim Hizmetleri San. Dış. Tic Ltd. Şti.", 'Adres' => "Esentepe Mah. Kardeşler Sok. (Metrocity Arkası) , Şişli,  İstanbul", 'Sehir' => "İstanbul", 'PostaKodu' => "34394", 'Ulke' => "Türkiye", 'Telefon' => "02122848433" ),
            array( 'SaticiAdi' => "Fotoğrafium", 'İletisimAdi' => "Alisverisium Sanal Mağ.Paz.", 'Adres' => "Ankara Cad. Kefeli İş Hanı No:227 Kat:2 Sirkeci-Eminönü / İstanbul", 'Sehir' => "İstanbul", 'PostaKodu' => "34110", 'Ulke' => "Türkiye", 'Telefon' => "08504333686" ),
        );
        shuffle($saticilar);
        foreach ($saticilar as $satici) {
            $saticiEkle = $this->db->prepare("INSERT INTO Saticilar(SaticiAdi, İletisimAdi, Adres, Sehir, PostaKodu, Ulke, Telefon) VALUES (:saticiAdi, :iletisimAdi, :adres, :sehir, :postaKodu, :ulke, :telefon);");
            $saticiEkle->execute(array(
                ':saticiAdi'   => $satici["SaticiAdi"],
                ':iletisimAdi' => $satici["İletisimAdi"],
                ':adres'       => $satici["Adres"],
                ':sehir'       => $satici["Sehir"],
                ':postaKodu'   => $satici["PostaKodu"],
                ':ulke'        => $satici['Ulke'],
                ':telefon'     => $satici["Telefon"]
            ));
        }
        echo "+ Satıcı Firmalar başarılı bir şeklide eklendi.\n";

        echo "+ Kategoriler tablosuna 8 adet Kategori ekleniyor...\n";
        $kategoriler = array(
            array( 'KategoriAdi' => "Bilgisayar", 'Aciklama' => "Masaüstü & Dizistü Bilgisayar, işlemci, tablet, ram, HDD vb." ),
            array( 'KategoriAdi' => "Giyim", 'Aciklama' => "Mont, t-shirt, kot vb." ),
            array( 'KategoriAdi' => "Kitap", 'Aciklama' => "Araştırma, inceleme, roman, hikaye, masal vb." ),
            array( 'KategoriAdi' => "Beyaz Eşya", 'Aciklama' => "Buzdolabı, fırın, ocak vb." ),
            array( 'KategoriAdi' => "Elektronik", 'Aciklama' => "Devreler, motorlar, sensörler vb." ),
            array( 'KategoriAdi' => "Müzik Ekipmanları", 'Aciklama' => "Klavyeler, pianolar, kemanlar, diğer enstrümanlar vb." ),
            array( 'KategoriAdi' => "Kişisel Bakım", 'Aciklama' => "Kişisel bakım ürünleri..." ),
            array( 'KategoriAdi' => "Fotoğraf & Video Kamera", 'Aciklama' => "DSLR makine, lens, filtre, objektif, video kamera vb." ),
        );
        shuffle($kategoriler);
        foreach ($kategoriler as $kategori) {
            $kategoriEkle = $this->db->prepare("INSERT INTO Kategoriler(KategoriAdi, Aciklama) VALUES (:kategoriAdi, :aciklama)");
            $kategoriEkle->execute(array(
                ':kategoriAdi' => $kategori["KategoriAdi"],
                ':aciklama'    => $kategori["Aciklama"]
            ));
        }
        echo "+ Kategoriler başarılı bir şekilde eklendi.\n";

        echo "+ Urunler tablosuna 49 adet Ürün ekleniyor...\n";

        $urunler = array(
            array( 'UrunAdi' => "Fırtınalı Denizin Yolcuları (Sedat Göçmen, İlbay Kahraman)", 'SaticiID' => $this->saticiIDGetir("İdefix"), 'KategoriID' => $this->kategoriIDGetir("Kitap"), 'Stok' => "10", 'Fiyat' => "18.2" ),
            array( 'UrunAdi' => "Apple iPad Air 16GB", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Bilgisayar"), 'Stok' => "50", 'Fiyat' => "999.0" ),
            array( 'UrunAdi' => "Samsung Galaxy Tab 3 Lite", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Bilgisayar"), 'Stok' => "18", 'Fiyat' => "259.0" ),
            array( 'UrunAdi' => "Dell Inspiron 3542", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Bilgisayar"), 'Stok' => "3", 'Fiyat' => "1598" ),
            array( 'UrunAdi' => "Asus Nvidia GeForce GTX 750 Ti", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Bilgisayar"), 'Stok' => "8", 'Fiyat' => "428.66" ),
            array( 'UrunAdi' => "Amd A4 4000", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Bilgisayar"), 'Stok' => "11", 'Fiyat' => "98.73" ),
            array( 'UrunAdi' => "Ziyan (Hakan Günday)", 'SaticiID' => $this->saticiIDGetir("Kitapyurdu"), 'KategoriID' => $this->kategoriIDGetir("Kitap"), 'Stok' => "6", 'Fiyat' => "17.6" ),
            array( 'UrunAdi' => "Küçük Kara Balık (Samed Behrengi)", 'SaticiID' => $this->saticiIDGetir("İdefix"), 'KategoriID' => $this->kategoriIDGetir("Kitap"), 'Stok' => "20", 'Fiyat' => "5.53" ),
            array( 'UrunAdi' => "Ud Desenli Kravat", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Giyim"), 'Stok' => "42", 'Fiyat' => "9.9" ),
            array( 'UrunAdi' => "Bambi Erkek Ayakkabı Kahverengi", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Giyim"), 'Stok' => "3", 'Fiyat' => "179.99" ),
            array( 'UrunAdi' => "Köstebek Charlie Chaplin Erkek T-Shirt", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Giyim"), 'Stok' => "33", 'Fiyat' => "14.9" ),
            array( 'UrunAdi' => "Galvanni Erkek Gömlek Mavi", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Giyim"), 'Stok' => "5", 'Fiyat' => "63.99" ),
            array( 'UrunAdi' => "Puma Kadın T-Shirt", 'SaticiID' => $this->saticiIDGetir("Kaft"), 'KategoriID' => $this->kategoriIDGetir("Giyim"), 'Stok' => "15", 'Fiyat' => "39.9" ),
            array( 'UrunAdi' => "Kalamus Çanta", 'SaticiID' => $this->saticiIDGetir("Kaft"), 'KategoriID' => $this->kategoriIDGetir("Giyim"), 'Stok' => "12", 'Fiyat' => "74.9" ),
            array( 'UrunAdi' => "Vestel BMJ L509 X  Bulaşık Makinası", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Beyaz Eşya"), 'Stok' => "55", 'Fiyat' => "898.98" ),
            array( 'UrunAdi' => "Deliliğe Övgü (Erasmus)", 'SaticiID' => $this->saticiIDGetir("İdefix"), 'KategoriID' => $this->kategoriIDGetir("Kitap"), 'Stok' => "42", 'Fiyat' => "9.75" ),
            array( 'UrunAdi' => "Posta Kutusundaki Mızıka (A. Ali Ural)", 'SaticiID' => $this->saticiIDGetir("Kitapyurdu"), 'KategoriID' => $this->kategoriIDGetir("Kitap"), 'Stok' => "2", 'Fiyat' => "8.4" ),
            array( 'UrunAdi' => "Sefiller (Victor Hugo)", 'SaticiID' => $this->saticiIDGetir("İdefix"), 'KategoriID' => $this->kategoriIDGetir("Kitap"), 'Stok' => "4", 'Fiyat' => "11.5" ),
            array( 'UrunAdi' => "Sunny SN8OCKE03 Elektrikli Ocak", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Beyaz Eşya"), 'Stok' => "10", 'Fiyat' => "39.9" ),
            array( 'UrunAdi' => "Arduino (Çoşkun Taşdemir)", 'SaticiID' => $this->saticiIDGetir("Dikeyeksen"), 'KategoriID' => $this->kategoriIDGetir("Kitap"), 'Stok' => "50", 'Fiyat' => "25.0" ),
            array( 'UrunAdi' => "Access Virus TI II Klavye", 'SaticiID' => $this->saticiIDGetir("Compel"), 'KategoriID' => $this->kategoriIDGetir("Müzik Ekipmanları"), 'Stok' => "7", 'Fiyat' => "2499.0" ),
            array( 'UrunAdi' => "Arturia Keylab 49 ", 'SaticiID' => $this->saticiIDGetir("Compel"), 'KategoriID' => $this->kategoriIDGetir("Müzik Ekipmanları"), 'Stok' => "150", 'Fiyat' => "329.0" ),
            array( 'UrunAdi' => "Calvin Klein One Edt 100 Ml Unisex Parfüm", 'SaticiID' => $this->saticiIDGetir("Hepsiburada"), 'KategoriID' => $this->kategoriIDGetir("Kişisel Bakım"), 'Stok' => "52", 'Fiyat' => "59.9" ),
            array( 'UrunAdi' => "Avon Just Move Erkek Parfüm", 'SaticiID' => $this->saticiIDGetir("Gittigidiyor"), 'KategoriID' => $this->kategoriIDGetir("Kişisel Bakım"), 'Stok' => "43", 'Fiyat' => "18.9" ),
            array( 'UrunAdi' => "Avon Pur Blanca Edp Bayan Parfüm", 'SaticiID' => $this->saticiIDGetir("Gittigidiyor"), 'KategoriID' => $this->kategoriIDGetir("Kişisel Bakım"), 'Stok' => "4", 'Fiyat' => "48.9" ),
            array( 'UrunAdi' => "Faulhaber 12V 120 RPM Motor", 'SaticiID' => $this->saticiIDGetir("Robotistan"), 'KategoriID' => $this->kategoriIDGetir("Elektronik"), 'Stok' => "7", 'Fiyat' => "303.91" ),
            array( 'UrunAdi' => "Ağırlık Sensörü", 'SaticiID' => $this->saticiIDGetir("Robotistan"), 'KategoriID' => $this->kategoriIDGetir("Elektronik"), 'Stok' => "38", 'Fiyat' => "13.02" ),
            array( 'UrunAdi' => "CC2541 Bluetooth 4.0 Serial Modül Kartı", 'SaticiID' => $this->saticiIDGetir("Robotistan"), 'KategoriID' => $this->kategoriIDGetir("Elektronik"), 'Stok' => "14", 'Fiyat' => "52.07" ),
            array( 'UrunAdi' => "Yaşar Ne Yaşar Ne Yaşamaz (Aziz Nesin)", 'SaticiID' => $this->saticiIDGetir("Kitapyurdu"), 'KategoriID' => $this->kategoriIDGetir("Elektronik"), 'Stok' => "52", 'Fiyat' => "15.2" ),
            array( 'UrunAdi' => "Brauner Phanthera Basic Mikrofon", 'SaticiID' => $this->saticiIDGetir("Compel"), 'KategoriID' => $this->kategoriIDGetir("Müzik Ekipmanları"), 'Stok' => "1", 'Fiyat' => "1349.0" ),
            array( 'UrunAdi' => "Apogee Duet Ses Kartı", 'SaticiID' => $this->saticiIDGetir("Compel"), 'KategoriID' => $this->kategoriIDGetir("Müzik Ekipmanları"), 'Stok' => "9", 'Fiyat' => "779.0" ),
            array( 'UrunAdi' => "Modern JavaScript (Fatih Kadir Akın)", 'SaticiID' => $this->saticiIDGetir("Dikeyeksen"), 'KategoriID' => $this->kategoriIDGetir("Kitap"), 'Stok' => "4", 'Fiyat' => "25.0" ),
            array( 'UrunAdi' => "SPSS ile Veri Madenciliği (Ali Osman Pektaş)", 'SaticiID' => $this->saticiIDGetir("Dikeyeksen"), 'KategoriID' => $this->kategoriIDGetir("Kitap"), 'Stok' => "2", 'Fiyat' => "20.0" ),
            array( 'UrunAdi' => "Lenovo G505 A4-5000 Dizüstü Bilgisayar", 'SaticiID' => $this->saticiIDGetir("Gittigidiyor"), 'KategoriID' => $this->kategoriIDGetir("Bilgisayar"), 'Stok' => "21", 'Fiyat' => "829.0" ),
            array( 'UrunAdi' => "Arduino Nano", 'SaticiID' => $this->saticiIDGetir("Gittigidiyor"), 'KategoriID' => $this->kategoriIDGetir("Elektronik"), 'Stok' => "54", 'Fiyat' => "21.97" ),
            array( 'UrunAdi' => "Canon Pixma MG 2450 Yazıcı", 'SaticiID' => $this->saticiIDGetir("Gold Computer"), 'KategoriID' => $this->kategoriIDGetir("Bilgisayar"), 'Stok' => "90", 'Fiyat' => "99.0" ),
            array( 'UrunAdi' => "Vestel 42PF8175 LED Tv", 'SaticiID' => $this->saticiIDGetir("Gold Computer"), 'KategoriID' => $this->kategoriIDGetir("Elektronik"), 'Stok' => "10", 'Fiyat' => "1399.0" ),
            array( 'UrunAdi' => "Arçelik 7110 RA Inverter Klima", 'SaticiID' => $this->saticiIDGetir("Gold Computer"), 'KategoriID' => $this->kategoriIDGetir("Beyaz Eşya"), 'Stok' => "40", 'Fiyat' => "7399.0" ),
            array( 'UrunAdi' => "Orvach Erkek T-Shirt", 'SaticiID' => $this->saticiIDGetir("Kaft"), 'KategoriID' => $this->kategoriIDGetir("Giyim"), 'Stok' => "70", 'Fiyat' => "39.9" ),
            array( 'UrunAdi' => "Dradz Çanta", 'SaticiID' => $this->saticiIDGetir("Kaft"), 'KategoriID' => $this->kategoriIDGetir("Giyim"), 'Stok' => "22", 'Fiyat' => "74.9" ),
            array( 'UrunAdi' => "Samsung WB150F Beyaz Dijital Fotoğraf Makinesi", 'SaticiID' => $this->saticiIDGetir("Teknosa"), 'KategoriID' => $this->kategoriIDGetir("Fotoğraf & Video Kamera"), 'Stok' => "68", 'Fiyat' => "399.0" ),
            array( 'UrunAdi' => "Nikon D3300 18-105mm VR Kit DSLR Fotoğraf Makinesi", 'SaticiID' => $this->saticiIDGetir("Fotoğrafium"), 'KategoriID' => $this->kategoriIDGetir("Fotoğraf & Video Kamera"), 'Stok' => "101", 'Fiyat' => "2349" ),
            array( 'UrunAdi' => "Tamron 70-300mm f/4-5.6 Di LD Macro Lens", 'SaticiID' => $this->saticiIDGetir("Fotoğrafium"), 'KategoriID' => $this->kategoriIDGetir("Fotoğraf & Video Kamera"), 'Stok' => "23", 'Fiyat' => "319.0" ),
            array( 'UrunAdi' => "Samsung 2,5\" 1TB M3 Taşınabilir Disk", 'SaticiID' => $this->saticiIDGetir("Vatan Bilgisayar"), 'KategoriID' => $this->kategoriIDGetir("Bilgisayar"), 'Stok' => "127", 'Fiyat' => "159.0" ),
            array( 'UrunAdi' => "Panasonic HC_V100 Video Kamera", 'SaticiID' => $this->saticiIDGetir("Vatan Bilgisayar"), 'KategoriID' => $this->kategoriIDGetir("Fotoğraf & Video Kamera"), 'Stok' => "0", 'Fiyat' => "539.0" ),
            array( 'UrunAdi' => "LG 65UB950 LED Tv", 'SaticiID' => $this->saticiIDGetir("Webdenal"), 'KategoriID' => $this->kategoriIDGetir("Elektronik"), 'Stok' => "3", 'Fiyat' => "6.9" ),
            array( 'UrunAdi' => "Bosch HGV74X456T Fırın", 'SaticiID' => $this->saticiIDGetir("Teknosa"), 'KategoriID' => $this->kategoriIDGetir("Beyaz Eşya"), 'Stok' => "8", 'Fiyat' => "1699.0" ),
            array( 'UrunAdi' => "Tefal PREMISS Baskül", 'SaticiID' => $this->saticiIDGetir("Teknosa"), 'KategoriID' => $this->kategoriIDGetir("Kişisel Bakım"), 'Stok' => "12", 'Fiyat' => "39.9" ),
            array( 'UrunAdi' => "Rossmax B1701 Tansiyon Ölçer", 'SaticiID' => $this->saticiIDGetir("Teknosa"), 'KategoriID' => $this->kategoriIDGetir("Kişisel Bakım"), 'Stok' => "85", 'Fiyat' => "45.7" ),
        );
        shuffle($urunler);
        foreach ($urunler as $urun) {
            $urunEkle = $this->db->prepare("INSERT INTO Urunler(UrunAdi, SaticiID, KategoriID, Stok, Fiyat) VALUES (:urunAdi, :saticiID, :kategoriID, :stok, :fiyat)");
            $urunEkle->execute(array(
                ':urunAdi'    => $urun["UrunAdi"],
                ':saticiID'   => $urun["SaticiID"],
                ':kategoriID' => $urun["KategoriID"],
                ':stok'       => $urun["Stok"],
                ':fiyat'      => $urun["Fiyat"]
            ));
        }
        echo "+ Ürünler başarılı bir şekilde eklendi.\n";

        $yapilacakSatis = rand(70, 200);
        echo "+ Veritabanınızda " . $yapilacakSatis . " adet sipariş ekleniyor...\n";
        for ($i = 0; $i < $yapilacakSatis; $i++) {
            $musteriID = rand(1, $eklenecekMusteriSayisi);
            $calisanID = rand(1, $eklenecekCalisanSayisi);
            $kargoFirmaID = rand(1, 4);
            $siparisTarihi = rand(1, 28) . "/" . rand(1, 12) . "/" . rand(1980, 2014);

            $siparisEkle = $this->db->prepare("INSERT INTO Siparisler(MusteriID, CalisanID, SiparisTarihi, KargoFirmaID) VALUES (:musteriID, :calisanID, :siparisTarihi, :kargoFirmaID)");
            $siparisEkle->execute(array(
                ':musteriID'     => $musteriID,
                ':calisanID'     => $calisanID,
                ':siparisTarihi' => $siparisTarihi,
                ':kargoFirmaID'  => $kargoFirmaID
            ));

            $siparisID = $this->db->lastInsertId();
            $satilanUrunSayisi = rand(1, 7);
            for ($j = 0; $j < $satilanUrunSayisi; $j++) {
                $urunID = rand(1, 49);
                $adet = rand(1, 10);

                $satisDetayEkle = $this->db->prepare("INSERT INTO SiparisDetaylari(SiparisID, UrunID, Adet) VALUES (:siparisID, :urunID, :adet)");
                $satisDetayEkle->execute(array(
                    ':siparisID' => $siparisID,
                    ':urunID'    => $urunID,
                    ':adet'      => $adet
                ));
            }
        }
        echo "+ Veritabanınıza Siparişler ve Sipariş Detayları başarılı bir şekilde eklendi.\n\n";
        echo "+ Rastgele veritabanınız başarılı bir şekilde oluşturuldu.\n";
    }

    /**
     * @param string $path Database file location
     */
    public function copyDatabaseFileTo($path) {
        if(file_exists('database.db')) {
            copy('database.db', $path);
            echo "+ Veritabanı dosyanız ana dizine kopyalandı.";
        }
    }

    /**
     * @param string $saticiAdi Satıcı Adı
     *
     * @return int Satıcı ID
     */
    private function saticiIDGetir($saticiAdi) {
        $saticiGetir = $this->db->prepare("SELECT SaticiID FROM Saticilar WHERE SaticiAdi=:saticiAdi");
        $saticiGetir->execute(array( ':saticiAdi' => $saticiAdi ));

        return $saticiGetir->fetchColumn(0);
    }

    /**
     * @param string $kategoriAdi Kategori Adı
     *
     * @return int Kategori ID
     */
    private function kategoriIDGetir($kategoriAdi) {
        $kategoriGetir = $this->db->prepare("SELECT KategoriID FROM Kategoriler WHERE KategoriAdi=:kategoriAdi");
        $kategoriGetir->execute(array( ':kategoriAdi' => $kategoriAdi ));

        return $kategoriGetir->fetchColumn(0);
    }

    function __destruct() {
        unset($this->db);
        unset($this->kisiler);
    }
}