# W3C SQL Editor with Turkish Database [![Version](https://img.shields.io/badge/v1.0-beta-red.svg)](#) [![License MIT](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/erenhatirnaz/w3c-sql-editor-with-turkish-database/blob/master/LICENSE) [![For Education](https://img.shields.io/badge/for-education-green.svg)](#) 

W3C'deki [SQL editörünün](http://www.w3schools.com/sql/trysql.asp?filename=trysql_select_all) Türkçe veriler, tablo isimleri ve kolon isimleri kullanılarak hazırlanmış halidir. Projeyi geliştirme amacım eğitim alanında üniversitelerde olsun liselerde olsun ingilizce sıkınıtısı olan yerlerde kullanılabilecek bir Türkçe alternatif oluşturmak. Sistemi geliştirmeye devam edeceğim. Eğer bir hata keşfederseniz [yeni bir ıssues açarak](https://github.com/erenhatirnaz/w3c-sql-editor-with-turkish-database/issues/new) bana bildirebilir yada [e-posta](mailto:erenhatirnaz@hotmail.com.tr) yoluyla bana ulaşabilirsiniz.

**NOT:  Veritabanındaki Türkçe kişi isimleri [Hüseyin Demirtaş](http://huseyindemirtas.net/) tarafından tamamen rastgele bir şekilde oluşturulmuştur. İlgili blog yazısı için [tıklayın](http://huseyindemirtas.net/rastgele-turkce-ad-soyad-kombinasyonlari/).**

## Kurulum
### İndirme
Dosyaları indirmek için:
```sh
git clone https://github.com/erenhatirnaz/w3c-sql-editor-with-turkish-database.git
```
yada direkt olarak yanda bulunan '**Download ZIP**' butonuna tıklayarak sıkıştırılmış dosya formatı şeklinde indirebilirsiniz.

### Çalıştırma
Dosyaları web sunucunuzdaki(apache, nginx vb.) bir klasöre attıktan sonra tarayıcınızdan ilgili klasör adresine girin. Ben direkt olarak indirdiğim şekilde klasör adını değiştirmeden web sunucuma attığım için `localhost/w3c-sql-editor-with-turkish-database` adresine girdim. Bir bilgilendirme mesajı ile karşılaşacaksınız bu aşamada '**Oluştur**' butonuna tıklamanız yeterli olacaktır. Daha sonra veritabanınız otomatik olarak rastgele bir şekilde oluşturulacaktır. Son olarak da '**Tamamlandı**' butonuna basarak kurulum işlemini bitirebilirsiniz. Artık sistem kullanıma hazır.

**NOT:** Linux sistemlerde, veritabanının rastgele oluşturulması sırasında "***permission denied***"  hatası ile karşılaşabilirsiniz. Bu durumda öncelikle `sudo -i` yazarak root izinlerini aldıktan sonra, dosyaların olduğu klasör için şu komutu çalıştırarak 777 izinlerini vermeniz gerekiyor:
```sh
chmod -R 777 w3c-sql-editor-with-turkish-database/
```
yada yine öncelikle root izinlerini aldıktan sonra şu komut ile direk konsol üzerinden php dosyasını çalıştırarak veritabanınızı oluşturabilirsiniz:
```sh
php tools/generator.php
```
Eğer bu yöntemler ile de veritabanınızı oluşturamadıysanız lütfen aldığınız hata mesajı ile birlikte [yeni bir issue açarak](https://github.com/erenhatirnaz/w3c-sql-editor-with-turkish-database/issues/new) durumu bana bildirin. 

## Eklenecekler Listesi / To - Do List
- Veritabanındaki verileri, her çalıştırıldığında rastgele bir şekilde oluşturacak php scripti. - **Tamamlandı ✓**
- Veritabanı üzerinde yapılacak olan değişiklikleri(UPDATE, DELETE, INSERT vb.) geri alacak olan php scripti. - **Listede.**

## Teşekkürler / Thanks
- [Hüseyin Demirtaş](http://huseyindemirtas.net/)'a, derlemiş olduğu rastgele türkçe ad soyad listesini kullanmama izin verdiği için teşekkür ederim. 

## Hakkımda / About Me
- [Blog](http://www.erenhatirnaz.wordpress.com)
- [Türkçe otobiyorafi](http://www.erenhatirnaz.kimdir.com)
- [English autobiography](http://www.about.me/ErenHatirnaz)
- [Linkedin](https://www.linkedin.com/in/erenhatirnaz)
- [Facebook](http://www.facebook.com/ErenHatirnaz)
- [Twitter](http://www.twitter.com/ErenHatirnaz)