<?php
/******************************************************************************************
 * Copyright (C) Smackcoders. - All Rights Reserved under Smackcoders Proprietary License
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/

namespace Smackcoders\CFCSV;

if ( ! defined( 'ABSPATH' ) )
exit; // Exit if accessed directly

class LangTR {
        private static $turkish_instance = null , $media_instance;

        public static function getInstance() {
                if (LangTR::$turkish_instance == null) {
                        LangTR::$turkish_instance = new LangTR;
                        return LangTR::$turkish_instance;
                }
                return LangTR::$turkish_instance;
        }

        public static function contents(){
                $response = array('ImportUpdate' => 'Güncellemeyi İçe Aktar',
                        'Dashboard' => 'Gösterge Paneli',
                        'Manager' => 'Yönetici',
                        'Export' => 'İhracat',
                        'Settings' => 'Ayarlar',
                        'Support' => 'Destek',
                        'UploadfromDesktop' => 'Masaüstünden Yükle',
                        'UploadfromFTPSFTP' => 'FTP SFTP den yükleyin',
                        'UploadfromURL' => "URL'den yükle",
                        'ChoosFileintheServer' => 'Sunucudaki Dosyayı Seçin',
                        'Drag&Dropyourfilesor' => 'Dosyalarınızı sürükleyip bırakın veya',
                        'Browse' => 'Araştır',
                        'NewItem' => 'Yeni öğe',
                        'Import' => 'İthalat',
                        'Update' => 'Güncelleme',
                        'ImportUpdates' => 'İçe Aktarma Güncelleme',
                        'ExistingItems' => 'Mevcut Öğeler',
                        'ImportEachRecordAs'=> 'Her Kaydı Farklı İçe Aktar',
                        'Continue' => 'Devam et',
                        'Search' => 'Arama',
                        'FromDate' => 'İtibaren',
                        'ToDate' => 'Bugüne kadar',
                        'SEARCH' => 'ARAMA',
                        'SavedTemplate' => 'Kaydedilmiş Şablon',
                        'TEMPLATES' => 'ŞABLONLAR',
                        'MATCHEDCOLUMNSCOUNT' => 'EŞLEŞEN SÜTUN SAYISI',
                        'MODULE' => 'MODÜL',
                        'CREATEDTIME' => 'OLUŞTURULAN ZAMAN',
                        'ACTION' => 'AKSİYON',
                        'USETEMPLATE' => 'ŞABLON KULLAN',
                        'CREATENEWMAPPING' => 'YENİ HARİTA OLUŞTUR',
                        'BACK' => 'GERİ',
                        'SIMPLEMODE' => 'BASİT MOD',
                        'ADVANCEDMODE' => 'GELİŞMİŞ MOD',
                        'DRAGDROPMODE' => 'DRAG DROP MODU',
                        'WordpressFields' => 'Wordpress Alanları',
                        'WPFIELDS' => 'RES ALANLARI',
                        'CSVHEADER' => 'CSV HEADER',
                        'Action' => 'Aksiyon',
                        'Name' => 'İsim',
                        'HINT' => 'İPUCU',
                        'Example' => 'Misal',
                        'WordPressCoreFields' => 'WordPress Çekirdek Dosyaları',
                        'ACFFreeFields' => 'ACF Serbest Alanlar',
                        'ACFFields' => 'ACF Alanları',
                        'ACFImageMetaFields' => 'ACF Görüntü Meta Alanları',
                        'ACFGroupFields' => 'ACF Group Fields',
                        'ACFProFields' => 'ACF Pro Alanları',
                        'ACFRepeaterFields' => 'ACF Tekrarlayıcı Alanları',
                        'ACFFlexibleFields' => 'ACF Esnek Alanları',
                        'JobListingFields' => 'İş Listeleme Alanları',
                        'TypesCustomFields' => 'Türler Özel Alanlar',
                        'TypesImageMetaFields' => 'Görüntü Meta Alanları Türleri',
                        'PodsFields' => 'Kapsül Alanları',
                        'PodsImageMetaFields' => 'Bölmeler Görüntü Meta Alanları',
                        'CustomFieldSuite' => 'Özel Field Suite',
                        'AllInOneSeoFields' => 'Hepsi Bir Arada Seo Alanları',
                        'MetaBoxFields' => 'MetaBox Alanları',
                        'MetaBoxRelationFields' => 'MetaBox İlişki Alanları',
                        'YoastSeoFields' => 'Yoast Seo Alanları',
                        'RankMathFields' => 'Rank Math Alanları',
                        'RankMathProFields'=>'Rank Math Pro Alanları',
                        'BillingAndShippingInformation' => 'Fatura ve Nakliye Bilgileri',
                        'CustomFieldsWPMemberFields' => 'Özel Alanlar WP Üye Alanları',
                        'CustomFieldsMemberFields' => 'Özel Alanlar Üye Alanları',
                        'ProductMetaFields' => 'Ürün Meta Alanları',
                        'ProductImageMetaFields' => 'Ürün Galerisi Meta Alanları',
                        'OrderMetaFields' => 'Meta Alanları Sipariş Et',
                        'CouponMetaFields' => 'Kupon Meta Alanları',
                        'RefundMetaFields' => 'Geri Ödeme Meta Alanları',
                        'WPECommerceCustomFields' => 'WP ECommerce Özel Alanları',
                        'EventsManagerFields' => 'Etkinlik Yöneticisi Alanları',
                        'NextGENGalleryFields' => 'NextGEN Galeri Alanları',
                        'WPMLFields' => 'WPML Alanları',
                        'CMB2CustomFields' => 'CMB2 Özel Alanları',
                        'JetEngineFields' => 'Jet Motoru Alanları',
                        'JetEngineRFFields' => 'Jet Engine Tekrarlayıcı Alanları',
                        'JetEngineCPTFields' => 'Jet Engine CPT Alanları',
                        'JetEngineCPTRFFields' => 'Jet Engine CPT Tekrarlayıcı Alanları',
                        'JetEngineCCTFields' => 'Jet Engine CCT Alanları',
                        'JetEngineCCTRFFields' => 'Jet Engine CCT Tekrarlayıcı Alanları',
                        'JetEngineTaxonomyFields' => 'Jet Motoru Taksonomi Alanları',
                        'JetEngineTaxonomyRFFields' => 'Jet Motoru Taksonomisi RF Alanları',
                        'JetEngineRelationsFields' => 'Jet Motor İlişki Alanları',
                        'CourseSettingsFields' => 'Kurs Ayarları Alanları',
                        'CurriculumSettingsFields' => 'Müfredat Ayarları Alanları',
                        'QuizSettingsFields' => 'Sınav Ayarları Alanları',
                        'LessonSettingsFields' => 'Ders Ayarları Alanları',
                        'QuestionSettingsFields' => 'Soru Ayarları Alanları',
                        'OrderSettingsFields' => 'Sipariş Ayarları Alanları',
                        'replyattributesfields' => 'Özellik Alanlarını Yanıtla',
                        'forumattributesfields' => 'Forum Özellikleri Alanları',
                        'topicattributesfields' => 'Konu Öznitelikleri Alanları',
                        'polylangfields'=>'Polylang Ayarları Alanları',
                        'WordPressCustomFields' => 'WordPress Özel Alanları',
                        'DirectoryProFields' => 'Directory Pro Alanları',
                        'TermsandTaxonomies' => 'Şartlar ve Sınıflandırmalar',
                        'IsSerialized' => 'Serileştirildi',
                        'NoCustomFieldsFound' => 'Özel Alan Bulunamadı', 
                        'MediaUploadFields' => 'Medya Yükleme Alanları',
                        'UploadMedia' => 'Medya Yükle',
                        'UploadedListofFiles' => 'Yüklenen Dosya Listesi',
                        'UploadedMediaFileLists' => 'Yüklenen Medya Dosyası Listesi',
                        'SavethismappingasTemplate' => 'Bu eşlemeyi Şablon olarak kaydedin',
                        'Save' => 'Kayıt etmek',
                        'Doyouneedtoupdatethecurrentmapping' => 'Mevcut eşlemeyi güncellemeniz gerekiyor mu',
                        'Savethecurrentmappingasnewtemplate' => 'Mevcut eşlemeyi yeni şablon olarak kaydedin',
                        'Back' => 'Geri',
                        'Size' => 'Boyut',
                        'MediaHandling' => 'Medyayı kontrol etme',
                        'Downloadexternalimagestoyourmedia' => 'Dış görüntüleri medyanıza indirin',
                        'ImageHandling' => 'Görüntü İşleme',
                        'Usemediaimagesifalreadyavailable' => 'Zaten mevcutsa medya görüntülerini kullanın',
                        'Doyouwanttooverwritetheexistingimages' => 'Mevcut görüntülerin üzerine yazmak istiyor musunuz',
                        'ImageSizes' => 'Resim Boyutları',
                        'Thumbnail' => 'Küçük resim',
                        'Medium' => 'Orta',
                        'MediumLarge' => 'Orta Büyük',
                        'Large' => 'Büyük',
                        'Custom' => 'Özel',
                        'Slug' => 'Sümüklüböcek',
                        'Width' => 'Genişlik',
                        'Height' => 'Yükseklik',
                        'Addcustomsizes' => 'Özel Boyutlar Ekleyin',
                        'PostContentImageOption' => 'İçerik Gönderme Seçeneği',
                        'DownloadPostContentExternalImagestoMedia' => 'Yayın İçeriği Harici Görsellerini Ortama İndirin',
                        'MediaSEOAdvancedOptions' => 'Medya SEO Gelişmiş Seçenekleri',
                        'SetimageTitle' => "Görüntü Başlığını Ayarla",
                        'SetimageCaption' => 'Resim Başlığını Ayarla',
                        'SetimageAltText' => 'Görüntü Alt Metni ayarla',
                        'SetimageDescription' => "Görüntü açıklamasını ayarla",
                        'Changeimagefilenameto' => 'Görüntü dosya adını şu şekilde değiştirin:',
                        'ImportconfigurationSection' => 'Konfigürasyon Bölümünü İçe Aktar',
                        'EnablesafeprestateRollback' => 'Güvenli ön hazırlık Geri Almayı etkinleştir',
                        'Backupbeforeimport' => "İçe aktarmadan önce yedekleyin",
                        'DoyouwanttoSWITCHONMaintenancemodewhileimport' => "İçe aktarırken Bakım modunu AÇMAK istiyor musunuz?",
                        'Doyouwanttohandletheduplicateonexistingrecords' => 'Mevcut kayıtlardaki kopyayı işlemek istiyor musunuz',
                        'Mentionthefieldswhichyouwanttohandleduplicates' => 'Yinelemeleri işlemek istediğiniz alanlardan bahsedin',
                        'DoyouwanttoUpdateanexistingrecords' => 'Mevcut bir kaydı güncellemek istiyor musunuz',
                        'Updaterecordsbasedon' => 'Kayıtları şuna göre güncelle:',
                        'DoyouwanttoSchedulethisImport' => 'Bu İçe Aktarmayı Planlamak İstiyor musunuz',
                        'ScheduleDate' => 'Program Tarihi',
                        'ScheduleFrequency' => 'Program Sıklığı',
                        'TimeZone' => 'Saat dilimi',
                        'ScheduleTime' => 'Zaman Çizelgesi',
                        'Schedule' => 'Program',
                        'Scheduled' => 'Scheduled',
                        'Import' => 'İthalat',
                        'Format' => 'Biçim',
                        'OneTime' => 'Bir kere',
                        'Daily' => 'Günlük',
                        'Weekly' => 'Haftalık',
                        'Monthly' => 'Aylık',
                        'Hourly' => 'Saatlik',
                        'Every4hours' => '4 saatte bir',
                        'Every2hours' => '2 saatte bir',
                        'Every30mins'=> '30 dakikada bir',
                        'Every15mins' => '15 dakikada bir',
                        'Every10mins' => 'Her 10 dakikada bir',
                        'Every5mins' => 'Her 5 dakikada bir',
                        'FileName' => 'Dosya adı',
                        'FileSize' => 'Dosya boyutu',
                        'Process' => 'İşlem',
                        'Totalnoofrecords' => 'Toplam kayıt sayısı',
                        'CurrentProcessingRecord' => 'Mevcut İşlem Kaydı',
                        'RemainingRecord' => 'Kalan Kayıt',
                        'Completed' => 'Tamamlandı',
                        'TimeElapsed' => 'Geçen süre',
                        'approximate' => 'yaklaşık',
                        'DownloadLog' => 'Günlüğü İndir',
                        'NoRecord' => 'NoRecord',
                        'UploadedCSVFileLists' => 'Yüklenen CSV Dosya Listeleri',
                        'Hostname' => 'Ana bilgisayar adı',
                        'HostPort' => 'Ana bilgisayar bağlantı noktası',
                        'HostUsername' => 'Ana Bilgisayar Kullanıcı Adı',
                        'HostPassword' => 'Ana Bilgisayar Şifresi',
                        'HostPath' => 'Ana Bilgisayar Yolu',
                        'DefaultPort' => 'Varsayılan Bağlantı Noktası',
                        'FTPUsername' => 'FTP Kullanıcı Adı',
                        'FTPPassword' => 'FTP Şifresi',
                        'ConnectionType' => 'Bağlantı türü',
                        'ImportersActivity' => 'İthalatçılar Faaliyeti',
                        'ImportStatistics' => 'İstatistikleri İçe Aktar',
                        'FileManager' => 'Dosya Yöneticisi',
                        'SmartSchedule' => 'Akıllı Program',
                        'ScheduledExport' => 'Planlanmış Dışa Aktarma',
                        'Templates' => 'Şablonlar',
                        'LogManager' => 'Günlük Yöneticisi',
                        'NotSelectedAnyTab' => 'Herhangi Bir Sekme Seçilmedi',
                        'EventInfo' => "Etkinlik Bilgileri",
                        'EventDate' => "Etkinlik tarihi",
                        'EventMode' => "Etkinlik Modu",
                        'EventStatus' => 'Etkinlik Durumu',
                        'Actions' => 'Hareketler',
                        'Date' => 'Tarih',
                        'Purpose' => 'Amaç',
                        'Revision' => 'Revizyon',
                        'Select' => 'Seçiniz',
                        'Inserted' => 'Eklendi',
                        'Updated' => 'Güncellenmiş',
                        'Skipped' => 'Atlandı',
                        'Delete' => 'Silmek',
                        'Noeventsfound' => 'Etkinlik Bulunamadı',
                        'ScheduleInfo' => 'Program Bilgisi',
                        'ScheduledDate' => 'Planlanmış tarih',
                        'ScheduledTime' => 'Planlanmış zaman',
                        'Youhavenotscheduledanyevent' => 'Herhangi bir etkinlik planlamadınız',
                        'Frequency' => 'Sıklık',
                        'Time' => 'Zaman',
                        'EditSchedule' => 'Programı Düzenle',
                        'SaveChanges' => 'Değişiklikleri Kaydet',
                        'TemplateInfo' => 'Şablon Bilgisi',
                        'TemplateName' => 'şablon adı',
                        'Module' => 'Modül',
                        'CreatedTime' => 'Oluşturulma Zamanı',
                        'NoTemplateFound' => 'Şablon Bulunamadı',
                        'Download' => 'İndir',
                        'NoLogRecordFound' => 'Günlük Kaydı Bulunamadı',
                        'GeneralSettings' => 'Genel Ayarlar',
                        'DatabaseOptimization' => 'Veritabanı Optimizasyonu',
                        'Media' =>'Medya',
                        'AccessKey' => 'Erişim anahtarı',
                        'SecurityandPerformance' => 'Security and Performance',
                        'Documentation' => 'Dokümantasyon',
                        'MediaReport' => 'Medya Raporu',
                        'DropTable' => 'Bırak Tablo',
                        'Ifenabledplugindeactivationwillremoveplugindatathiscannotberestored' => 'Etkinleştirilirse eklentinin devre dışı bırakılması eklenti verilerini kaldırır, bu geri yüklenemez',
                        'Scheduledlogmails' => 'Planlanmış Günlük Postaları',
                        'Enabletogetscheduledlogmails' => 'Planlanmış günlük postalarını almayı etkinleştirin',
                        'Sendpasswordtouser' => "Kullanıcıya şifre gönder",
                        'Enabletosendpasswordinformationthroughemail' => "E-posta yoluyla şifre bilgisi göndermeyi etkinleştirin",
                        'WoocommerceCustomattribute' => 'Woocommerce Özel özelliği',
                        'Enablestoregisterwoocommercecustomattribute' => "Woocommerce özel özniteliğini kaydetmeyi sağlar",
                        'PleasemakesurethatyoutakenecessarybackupbeforeproceedingwithdatabaseoptimizationThedatalostcantbereverted' => "Lütfen veritabanı optimizasyonuna geçmeden önce gerekli yedeği aldığınızdan emin olun. Kaybolan veriler geri alınamaz",
                        'DeleteallorphanedPostPageMeta' => 'Sahipsiz tüm Yazı Sayfası Metasını sil',
                        'Deleteallunassignedtags' => 'Atanmamış tüm etiketleri sil',
                        'DeleteallPostPagerevisions' => 'Tüm Yazı Sayfası revizyonlarını sil',
                        'DeleteallautodraftedPostPage' => 'Otomatik olarak hazırlanan tüm Gönderi Sayfasını sil',
                        'DeleteallPostPageintrash' => 'Çöp kutusundaki tüm Yazı Sayfalarını sil',
                        'DeleteallCommentsintrash' => 'Çöp kutusundaki tüm Yorumları sil',
                        'DeleteallUnapprovedComments' => 'Onaylanmamış Tüm Yorumları Sil',
                        'DeleteallPingbackComments' => 'Tüm Pingback Yorumlarını Sil',
                        'DeleteallTrackbackComments' => 'Tümünü Sil Geri İzleme Yorumları',
                        'DeleteallSpamComments' => 'Tüm Spam Yorumlarını Sil',
                        'RunDBOptimizer' => "DB Optimizer'ı çalıştırın",
                        'DatabaseOptimizationLog' => 'Veritabanı Optimizasyon Günlüğü',
                        'noofOrphanedPostPagemetahasbeenremoved' => 'Orphaned Post Page meta hiçbiri kaldırılmadı',
                        'noofUnassignedtagshasbeenremoved' => 'Atanmamış etiketlerin hiçbiri kaldırılmadı',
                        'noofPostPagerevisionhasbeenremoved' => 'Yazı Sayfası revizyonunun hiçbiri kaldırılmadı',
                        'noofAutodraftedPostPagehasbeenremoved' => 'Otomatik Hazırlanmış Gönderi Sayfası hiçbiri kaldırılmadı',
                        'noofPostPageintrashhasbeenremoved' => 'çöp kutusundaki Yazı Sayfası hiçbiri kaldırılmadı',
                        'noofSpamcommentshasbeenremoved' => 'Spam yorumlarından hiçbiri kaldırılmadı',
                        'noofCommentsintrashhasbeenremoved' => 'çöp kutusundaki Yorumlardan hiçbiri kaldırılmadı',
                        'noofUnapprovedcommentshasbeenremoved' => 'Onaylanmamış yorumlardan hiçbiri kaldırılmadı',
                        'noofPingbackcommentshasbeenremoved' => 'Pingback yorumlarından hiçbiri kaldırılmadı',
                        'noofTrackbackcommentshasbeenremoved' => 'Geri izleme yorumlarından hiçbiri kaldırılmadı',
                        'Allowauthorseditorstoimport' => 'Yazar editörlerinin içe aktarmasına izin ver',
                        'Thisenablesauthorseditorstoimport' => 'Bu, yazar editörlerinin',
                        'MinimumrequiredphpinivaluesIniconfiguredvalues' => 'Minimum gerekli php ini değerleri Ini yapılandırılmış değerler',
                        'Variables' => 'Değişkenler',
                        'SystemValues' => 'Sistem Değerleri',
                        'MinimumRequirements' => 'Minimum Gereksinimler',
                        'RequiredtoenabledisableLoadersExtentionsandmodules' => 'Yükleyici Uzantılarını ve modüllerini devre dışı bırakmak için gereklidir',
                        'DebugInformation' => 'Hata Ayıklama Bilgileri',
                        'SmackcodersGuidelines' => 'Smackcoders Yönergeleri',
                        'DevelopmentNews' => 'Geliştirme Haberleri',
                        'WhatsNew' => "Ne var ne yok",
                        'YoutubeChannel' => 'Youtube kanalı',
                        'OtherWordPressPlugins' => 'Diğer WordPress Eklentileri',
                        'Count' => 'Miktar',
                        'ImageType' => 'Resim türü',
                        'Status' => 'Durum',
                        'Loading' => 'Yükleniyor',
                        'LoveWPUltimateCSVImporterGivea5starreviewon' => "WP Ultimate CSV Importer'ı Sevin Hakkında 5 yıldızlı bir inceleme yapın",
                        'ContactSupport' => 'İletişim desteği',
                        'Email' => 'Eposta',
                        'OrderId' => 'Sipariş Kimliği',
                        'Supporttype' => 'Destek Türü',
                        'BugReporting' => 'Hata Raporlama',
                        'FeatureEnhancement' => 'Özellik Geliştirme',
                        'Message' => 'İleti',
                        'Send' => 'Gönder',
                        'NewsletterSubscription' => 'Bülten Aboneliği',
                        'Subscribe' => 'Abone ol',
                        'Note' => 'Not',
                        'SubscribetoSmackcodersMailinglistafewmessagesayear' => "Smackcoders Mailing listesine yılda birkaç mesaj abone olun",
                        'Pleasedraftamailto' => 'Lütfen bir posta taslağı oluşturun',
                        'Ifyoudoesnotgetanyacknowledgementwithinanhour' => "Bir saat içinde ile herhangi bir onay almazsan",
                        'Selectyourmoduletoexportthedata' => 'Verileri dışa aktarmak için modülünüzü seçin',
                        'Toexportdatabasedonthefilters' => 'Verileri filtrelere göre dışa aktarmak için',
                        'ExportFileName' => 'Dosya Adını Dışa Aktar',
                        'AdvancedSettings' => 'Gelişmiş Ayarlar',
                        'ExportType' => 'İhracat Tipi',
                        'SplittheRecord' => 'Kaydı Böl',
                        'AdvancedFilters'=> 'Gelişmiş Filtreler',
                        'Exportdatawithautodelimiters' => 'Otomatik sınırlayıcılarla verileri dışa aktarın',
                        'Delimiters' => 'Sınırlayıcılar',
                        'OtherDelimiters' => 'Diğer Sınırlayıcılar',
                        'Exportdataforthespecificperiod' => 'Belirli bir dönem için verileri dışa aktarın',
                        'StartFrom' => 'StartFrom',
                        'EndTo' => 'Son',
                        'Exportdatawiththespecificstatus' => 'Verileri belirli bir durumla dışa aktarın',
                        'All' => 'Herşey',
                        'Publish' => 'Yayınla',
                        'Sticky' => 'Yapışkan',
                        'Private' => 'Özel',
                        'Protected' => 'Protected',
                        'Draft' => 'Taslak',
                        'Pending' => 'Bekliyor',
                        'Exportdatabyspecificauthors' => 'Verileri belirli yazarlara göre dışa aktarın',
                        'Authors' => 'Yazarlar',
                        'Exportdatabyspecificcategory' => 'Verileri belirli kategoriye göre dışa aktarın',
                        'Category' => 'Kategori',
                        'ExportdatabasedonspecificInclusions' => 'Verileri belirli Eklentilere göre dışa aktarın',
                        'DoyouwanttoSchedulethisExport' => 'Bu Dışa Aktarmayı Planlamak İstiyor musunuz',
                        'SelectTimeZone' => 'Saat Dilimini Seçin',
                        'ScheduleExport' => 'Dışa Aktarmayı Zamanla',
                        'DataExported' => 'Dışa Aktarılan Veriler',
                        'elementorfields'=>'Elementor Alanları',
                        'License'=>'Lisans',
                        'ThankYouForYourPurchase'=>'Satın aldığınız için teşekkür ederiz',
                        'ToGetStartedYouNeedToActivateByEnteringTheLicensekey'=>'Başlamak için lisans anahtarını girerek etkinleştirmeniz gerekir',
                        'EnterTheLicenseKey'=>'Lisans Anahtarını Girin',
                        'LicenseDetails'=>'Lisans Ayrıntıları',
                        'ProductName'=>'Ürün adı',
                        'LicenseKey'=>'Lisans anahtarı',
                        'NoDataFound'=>'Veri bulunamadı',
                        'Activate'=>'Etkinleştir'
                );
        return $response;
        }
}