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

class LangRU {
        private static $russian_instance = null , $media_instance;

        public static function getInstance() {
                if (LangRU::$russian_instance == null) {
                        LangRU::$russian_instance = new LangRU;
                        return LangRU::$russian_instance;
                }
                return LangRU::$russian_instance;
        }

        public static function contents(){
                $response = array('ImportUpdate' => 'импорт',
                        'Dashboard' => 'Панель приборов',
                        'Manager' => 'Управляющий делами',
                        'Export' => 'Экспорт',
                        'Settings' => 'Настройки',
                        'Support' => 'Поддержка',
                        'UploadfromDesktop' => 'Загрузить с рабочего стола',
                        'UploadfromFTPSFTP' => 'Загрузить с FTP / SFTP',
                        'UploadfromURL' => 'Загрузить с URL',
                        'ChoosFileintheServer' => 'Выберите файл на сервере',
                        'Drag&Dropyourfilesor' => 'Перетащите файлы или',
                        'Browse' => 'Просматривать',
                        'NewItem' => 'Новый предмет',
                        'Import' => 'импорт',
                        'Update' => 'Обновить',
                        'ImportUpdates' => 'Импорт-Обновление',
                        'ExistingItems' => 'Существующие предметы',
                        'ImportEachRecordAs'=> 'Импортируйте каждую запись как',
                        'Continue' => 'Продолжать',
                        'Search' => 'Поиск',
                        'FromDate' => 'С даты',
                        'ToDate' => 'Встретиться',
                        'SEARCH' => 'ПОИСК',
                        'SavedTemplate' => 'Сохраненный шаблон',
                        'TEMPLATES' => 'ШАБЛОНЫ',
                        'MATCHEDCOLUMNSCOUNT' => 'СЧЕТЧИК СООТВЕТСТВУЮЩИХ КОЛОНН',
                        'MODULE' => 'МОДУЛЬ',
                        'CREATEDTIME' => 'СОЗДАННОЕ ВРЕМЯ',
                        'ACTION' => 'ДЕЙСТВИЕ',
                        'USETEMPLATE' => 'ИСПОЛЬЗОВАТЬ ШАБЛОН',
                        'CREATENEWMAPPING' => 'СОЗДАТЬ НОВУЮ КАРТУ',
                        'BACK' => 'НАЗАД',
                        'SIMPLEMODE' => 'ПРОСТОЙ РЕЖИМ',
                        'ADVANCEDMODE' => 'РАСШИРЕННЫЙ РЕЖИМ',
                        'DRAGDROPMODE' => 'РЕЖИМ DRAG & DROP',
                        'WordpressFields' => 'Поля Wordpress',
                        'WPFIELDS' => 'Поля WP',
                        'CSVHEADER' => 'Заголовок CSV',
                        'Action' => 'Действие',
                        'Name' => 'имя',
                        'HINT' => 'ПОДСКАЗКА',
                        'Example' => 'пример',
                        'WordPressCoreFields' => 'Основные поля WordPress',
                        'ACFFreeFields' => 'Бесплатные поля ACF',
                        'ACFFields' => 'Поля ACF',
                        'ACFImageMetaFields' => 'Мета-поля изображения ACF',
                        'ACFGroupFields' => 'Поля группы ACF',
                        'ACFProFields' => 'ACF Pro Fields',
                        'ACFRepeaterFields' => 'Поля ретранслятора ACF',
                        'ACFFlexibleFields' => 'Гибкие поля ACF',
                        'JobListingFields' => 'Поля списка вакансий',
                        'TypesCustomFields' => 'Типы настраиваемых полей',
                        'TypesImageMetaFields' => 'Типы метаполя изображения',
                        'PodsFields' => 'Поля стручков',
                        'PodsImageMetaFields' => 'Мета-поля изображений стручков',
                        'CustomFieldSuite' => 'Custom Field Suite',
                        'AllInOneSeoFields' => 'Все в одном поле для SEO',
                        'MetaBoxFields' => 'Поля MetaBox',
                        'MetaBoxRelationFields' => 'Поля отношений MetaBox',
                        'YoastSeoFields' => 'Йоаст Сео Филдс',
                        'BillingAndShippingInformation' => 'Информация о выставлении счетов и доставке',
                        'CustomFieldsWPMemberFields' => 'Настраиваемые поля Поля участников WP',
                        'CustomFieldsMemberFields' => 'Пользовательские поля Поля-члены',
                        'ProductMetaFields' => 'Мета-поля продукта',
                        'ProductImageMetaFields' => 'Галерея продуктов Мета-поля',
                        'OrderMetaFields' => 'Мета-поля заказа',
                        'CouponMetaFields' => 'Купонные мета-поля',
                        'RefundMetaFields' => 'Мета-поля возврата',
                        'WPECommerceCustomFields' => 'Пользовательские поля WP ECommerce',
                        'EventsManagerFields' => 'Поля диспетчера событий',
                        'NextGENGalleryFields' => 'Поля галереи NextGEN',
                        'WPMLFields' => 'Поля WPML',
                        'CMB2CustomFields' => 'CMB2 Пользовательские поля',
                        'JetEngineFields' => 'Области реактивного двигателя',
                        'JetEngineRFFields' => 'Поля повторителя реактивного двигателя',
                        'JetEngineCPTFields' => 'Поля реактивного двигателя CPT',
                        'JetEngineCPTRFFields' => 'Поля ретранслятора CPT реактивного двигателя',
                        'JetEngineCCTFields' => 'Поля реактивного двигателя CCT',
                        'JetEngineCCTRFFields' => 'Поля ретранслятора CCT реактивного двигателя',
                        'JetEngineTaxonomyFields' => 'Поля таксономии реактивных двигателей',
                        'JetEngineTaxonomyRFFields' => 'Поля РФ таксономии реактивных двигателей',
                        'JetEngineRelationsFields' => 'Поля отношений с реактивным двигателем',
                        'CourseSettingsFields' => 'Поля настроек курса',
                        'CurriculumSettingsFields' => 'Поля настроек учебной программы',
                        'QuizSettingsFields' => 'Поля настроек викторины',
                        'LessonSettingsFields' => 'Поля настроек урока',
                        'QuestionSettingsFields' => 'Поля настроек вопроса',
                        'OrderSettingsFields' => 'Поля настроек заказа',
                        'replyattributesfields' => 'Поля атрибутов ответа',
                        'forumattributesfields' => 'Поля атрибутов форума',
                        'topicattributesfields' => 'Поля атрибутов темы',
                        'polylangfields'=>'Поля настроек Polylang',
                        'WordPressCustomFields' => 'Пользовательские поля WordPress',
                        'DirectoryProFields' => 'Поля Directory Pro',
                        'TermsandTaxonomies' => 'Термины и таксономии',
                        'IsSerialized' => 'Сериализован',
                        'NoCustomFieldsFound' => 'Настраиваемые поля не найдены', 
                        'MediaUploadFields' => 'Поля загрузки мультимедиа',
                        'UploadMedia' => 'Загрузить медиа',
                        'UploadedListofFiles' => 'Загруженный список файлов',
                        'UploadedMediaFileLists' => 'Списки загруженных медиафайлов',
                        'SavethismappingasTemplate' => 'Сохранить это сопоставление как шаблон',
                        'Save' => 'Сохранить',
                        'Doyouneedtoupdatethecurrentmapping' => 'Вам нужно обновить текущее отображение?',
                        'Savethecurrentmappingasnewtemplate' => 'Сохранить текущее сопоставление как новый шаблон',
                        'Back' => 'Назад',
                        'Size' => 'Размер',
                        'MediaHandling' => 'Обработка СМИ',
                        'Downloadexternalimagestoyourmedia' => 'Загрузите внешние изображения на свой носитель',
                        'ImageHandling' => 'Обработка изображений',
                        'Usemediaimagesifalreadyavailable' => 'Используйте изображения мультимедиа, если они уже доступны',
                        'Doyouwanttooverwritetheexistingimages' => 'Вы хотите перезаписать существующие изображения',
                        'ImageSizes' => 'Размеры изображения',
                        'Thumbnail' => 'Эскиз',
                        'Medium' => 'средний',
                        'MediumLarge' => 'Средний Большой',
                        'Large' => 'Большой',
                        'Custom' => 'На заказ',
                        'Slug' => 'Слизняк',
                        'Width' => 'Ширина',
                        'Height' => 'Высота',
                        'Addcustomsizes' => 'Добавить нестандартные размеры',
                        'PostContentImageOption' => 'Вариант изображения содержания публикации',
                        'DownloadPostContentExternalImagestoMedia' => 'Загрузить внешние изображения содержимого публикации на носитель',
                        'MediaSEOAdvancedOptions' => 'Медиа SEO и дополнительные параметры',
                        'SetimageTitle' => 'Установить заголовок изображения',
                        'SetimageCaption' => 'Установить подпись к изображению',
                        'SetimageAltText' => 'Установить замещающий текст изображения',
                        'SetimageDescription' => 'Установить изображение Описание',
                        'Changeimagefilenameto' => 'Измените имя файла изображения на',
                        'ImportconfigurationSection' => 'Раздел конфигурации импорта',
                        'EnablesafeprestateRollback' => 'Включить безопасный откат до состояния',
                        'Backupbeforeimport' => 'Резервное копирование перед импортом',
                        'DoyouwanttoSWITCHONMaintenancemodewhileimport' => 'Вы хотите ВКЛЮЧИТЬ режим обслуживания во время импорта',
                        'Doyouwanttohandletheduplicateonexistingrecords' => 'Вы хотите обработать дубликаты существующих записей',
                        'Mentionthefieldswhichyouwanttohandleduplicates' => 'Укажите поля, в которых вы хотите обрабатывать дубликаты.',
                        'DoyouwanttoUpdateanexistingrecords' => 'Вы хотите обновить существующие записи',
                        'Updaterecordsbasedon' => 'Обновить записи на основе',
                        'DoyouwanttoSchedulethisImport' => 'Вы хотите запланировать этот импорт',
                        'ScheduleDate' => 'Дата расписания',
                        'ScheduleFrequency' => 'Расписание Частота',
                        'TimeZone' => 'Часовой пояс',
                        'ScheduleTime' => 'График времени',
                        'Schedule' => 'График',
                        'Scheduled' => 'по расписанию',
                        'Import' => 'Начать импорт',
                        'Format' => 'Формат',
                        'OneTime' => 'Один раз',
                        'Daily' => 'Повседневная',
                        'Weekly' => 'Еженедельно',
                        'Monthly' => 'Ежемесячно',
                        'Hourly' => 'Ежечасно',
                        'Every4hours' => 'Каждые 4 часа',
                        'Every2hours' => 'Каждые 2 часа',
                        'Every30mins'=> 'Каждые 30 минут',
                        'Every15mins' => 'Каждые 15 минут',
                        'Every10mins' => 'Каждые 10 минут',
                        'Every5mins' => 'Каждые 5 минут',
                        'FileName' => 'Имя файла',
                        'FileSize' => 'Размер файла',
                        'Process' => 'Процесс',
                        'Totalnoofrecords' => 'Всего нет записей',
                        'CurrentProcessingRecord' => 'Текущая запись обработки',
                        'RemainingRecord' => 'Оставшаяся запись',
                        'Completed' => 'Завершено',
                        'TimeElapsed' => 'Время истекло; истекшее время',
                        'approximate' => 'приблизительный',
                        'DownloadLog' => 'Посмотреть журнал',
                        'NoRecord' => 'Нет записи',
                        'UploadedCSVFileLists' => 'Загруженные списки файлов CSV',
                        'Hostname' => 'Имя хоста',
                        'HostPort' => 'Хост-порт',
                        'HostUsername' => 'Имя пользователя хоста',
                        'HostPassword' => 'Пароль хоста',
                        'HostPath' => 'Путь к хосту',
                        'DefaultPort' => 'Порт по умолчанию',
                        'FTPUsername' => 'Имя пользователя FTP',
                        'FTPPassword' => 'Пароль FTP',
                        'ConnectionType' => 'Тип соединения',
                        'ImportersActivity' => 'Деятельность импортеров',
                        'ImportStatistics' => 'Статистика импорта',
                        'FileManager' => 'Файловый менеджер',
                        'SmartSchedule' => 'Умное расписание',
                        'ScheduledExport' => 'Запланированный экспорт',
                        'Templates' => 'Шаблоны',
                        'LogManager' => 'Менеджер журнала',
                        'NotSelectedAnyTab' => 'Не выбрана ни одна вкладка',
                        'EventInfo' => 'Информация о мероприятии',
                        'EventDate' => 'Дата события',
                        'EventMode' => 'Режим события',
                        'EventStatus' => 'Статус события',
                        'Actions' => 'Действия',
                        'Date' => 'Дата',
                        'Purpose' => 'Цель',
                        'Revision' => 'Редакция',
                        'Select' => 'Выбрать',
                        'Inserted' => 'Вставлено',
                        'Updated' => 'Обновлено',
                        'Skipped' => 'Пропущено',
                        'Delete' => 'Удалить',
                        'Noeventsfound' => 'Мероприятий не найдено',
                        'ScheduleInfo' => 'Информация о расписании',
                        'ScheduledDate' => 'Запланированная дата',
                        'ScheduledTime' => 'Запланированное время',
                        'Youhavenotscheduledanyevent' => 'Вы не запланировали ни одного мероприятия',
                        'Frequency' => 'Частота',
                        'Time' => 'Время',
                        'EditSchedule' => 'Изменить расписание',
                        'SaveChanges' => 'Сохранить изменения',
                        'TemplateInfo' => 'Информация о шаблоне',
                        'TemplateName' => 'Имя Шаблона',
                        'Module' => 'Модуль',
                        'CreatedTime' => 'Время создания',
                        'NoTemplateFound' => 'Шаблон не найден',
                        'Download' => 'Скачать',
                        'NoLogRecordFound' => 'Запись в журнале не найдена',
                        'GeneralSettings' => 'общие настройки',
                        'DatabaseOptimization' => 'Оптимизация базы данных',
                        'Media' =>'СМИ',
                        'AccessKey' => 'Ключ доступа',
                        'SecurityandPerformance' => 'Безопасность и производительность',
                        'Documentation' => 'Документация',
                        'MediaReport' => 'Отчет СМИ',
                        'DropTable' => 'Drop Table',
                        'Ifenabledplugindeactivationwillremoveplugindatathiscannotberestored' => 'Если при включенной деактивации плагина будут удалены данные плагина, их нельзя будет восстановить.',
                        'Scheduledlogmails' => 'Запланированные сообщения журнала',
                        'Enabletogetscheduledlogmails' => 'Включите, чтобы получать сообщения журнала по расписанию.',
                        'Sendpasswordtouser' => 'Отправить пароль пользователю',
                        'Enabletosendpasswordinformationthroughemail' => 'Включите отправку информации о пароле по электронной почте.',
                        'WoocommerceCustomattribute' => 'Пользовательский атрибут ',
                        'Enablestoregisterwoocommercecustomattribute' => 'Позволяет зарегистрировать настраиваемый атрибут ',
                        'PleasemakesurethatyoutakenecessarybackupbeforeproceedingwithdatabaseoptimizationThedatalostcantbereverted' => 'Перед тем, как приступить к оптимизации базы данных, убедитесь, что вы сделали необходимую резервную копию. Потерянные данные не могут быть восстановлены.',
                        'DeleteallorphanedPostPageMeta' => 'Удалить все потерянные мета-сообщения / страницы',
                        'Deleteallunassignedtags' => 'Удалить все неназначенные теги',
                        'DeleteallPostPagerevisions' => 'Удалить все версии сообщения / страницы',
                        'DeleteallautodraftedPostPage' => 'Удалить все автоматически составленные сообщения / страницы',
                        'DeleteallPostPageintrash' => 'Удалить все сообщения / страницы в корзине',
                        'DeleteallCommentsintrash' => 'Удалить все комментарии в корзине',
                        'DeleteallUnapprovedComments' => 'Delete all Unapproved Comments',
                        'DeleteallPingbackComments' => 'Удалить все комментарии Pingback',
                        'DeleteallTrackbackComments' => 'Удалить все комментарии к треку',
                        'DeleteallSpamComments' => 'Удалить все спам-комментарии',
                        'RunDBOptimizer' => 'Запустить оптимизатор БД',
                        'DatabaseOptimizationLog' => 'Журнал оптимизации базы данных',
                        'noofOrphanedPostPagemetahasbeenremoved' => 'ни одна мета-версия потерянного сообщения / страницы не была удалена.',
                        'noofUnassignedtagshasbeenremoved' => 'ни один из неназначенных тегов не был удален.',
                        'noofPostPagerevisionhasbeenremoved' => 'ни одна из ревизий сообщения / страницы не была удалена.',
                        'noofAutodraftedPostPagehasbeenremoved' => 'ни одна из автоматически созданных сообщений / страниц не была удалена.',
                        'noofPostPageintrashhasbeenremoved' => 'ни одна из записей / страниц в корзине не была удалена.',
                        'noofSpamcommentshasbeenremoved' => 'ни один из спам-комментариев не удален.',
                        'noofCommentsintrashhasbeenremoved' => 'ни один из комментариев в корзине не был удален.',
                        'noofUnapprovedcommentshasbeenremoved' => 'Ни один из Неутвержденных комментариев не был удален.',
                        'noofPingbackcommentshasbeenremoved' => 'ни один из комментариев Pingback не был удален.',
                        'noofTrackbackcommentshasbeenremoved' => 'ни один из комментариев к трекбэку не был удален.',
                        'Allowauthorseditorstoimport' => 'Разрешить авторам / редакторам импортировать',
                        'Allowauthorseditorstoimport' => 'Разрешить авторам / редакторам импортировать',
                        'Thisenablesauthorseditorstoimport' => 'Это позволяет авторам / редакторам импортировать.',
                        'MinimumrequiredphpinivaluesIniconfiguredvalues' => 'Минимальные требуемые значения php.ini (значения, настроенные в Ini)',
                        'Variables' => 'Переменные',
                        'SystemValues' => 'Системные значения',
                        'MinimumRequirements' => 'Минимальные требования',
                        'RequiredtoenabledisableLoadersExtentionsandmodules' => 'Требуется для включения / отключения загрузчиков, расширений и модулей:',
                        'DebugInformation' => 'Информация об отладке:',
                        'SmackcodersGuidelines' => 'Рекомендации по Smackcoders',
                        'DevelopmentNews' => 'Новости развития',
                        'WhatsNew' => 'Что нового?',
                        'YoutubeChannel' => 'YouTube канал',
                        'OtherWordPressPlugins' => 'Другие плагины WordPress',
                        'Count' => 'Считать',
                        'ImageType' => 'Тип изображения',
                        'Status' => 'Статус',
                        'Loading' => 'Загрузка',
                        'LoveWPUltimateCSVImporterGivea5starreviewon' => 'Люблю WP Ultimate CSV Importer, дайте 5-звездочный обзор на',
                        'ContactSupport' => 'Контактная поддержка',
                        'Email' => 'Эл. адрес',
                        'OrderId' => 'номер заказа',
                        'Supporttype' => 'Тип поддержки',
                        'BugReporting' => 'Сообщение об ошибках',
                        'FeatureEnhancement' => 'Улучшение функции',
                        'Message' => 'Сообщение',
                        'Send' => 'послать',
                        'NewsletterSubscription' => 'Подписка на новости',
                        'Subscribe' => 'Подписывайся',
                        'Note' => 'Запись',
                        'SubscribetoSmackcodersMailinglistafewmessagesayear' => 'Подпишитесь на рассылку Smackcoders (несколько сообщений в год)',
                        'Pleasedraftamailto' => 'Напишите письмо на адрес',
                        'Ifyoudoesnotgetanyacknowledgementwithinanhour' => 'Если вы не получите подтверждения в течение часа!',
                        'Selectyourmoduletoexportthedata' => 'Выберите модуль для экспорта данных',
                        'Toexportdatabasedonthefilters' => 'Для экспорта данных на основе фильтров',
                        'ExportFileName' => 'Имя файла экспорта',
                        'AdvancedSettings' => 'Расширенные настройки',
                        'ExportType' => 'Тип экспорта',
                        'SplittheRecord' => 'Разделить запись',
                        'AdvancedFilters'=> 'Расширенные фильтры',
                        'Exportdatawithautodelimiters' => 'Экспорт данных с автоматическими разделителями',
                        'Delimiters' => 'Разделители',
                        'OtherDelimiters' => 'Другие разделители',
                        'Exportdataforthespecificperiod' => 'Экспорт данных за определенный период',
                        'StartFrom' => 'Начинать с',
                        'EndTo' => 'Конец',
                        'Exportdatawiththespecificstatus' => 'Экспорт данных с определенным статусом',
                        'All' => 'Все',
                        'Publish' => 'Публиковать',
                        'Sticky' => 'Липкий',
                        'Private' => 'Частный',
                        'Protected' => 'Защищено',
                        'Draft' => 'Проект',
                        'Pending' => 'В ожидании',
                        'Exportdatabyspecificauthors' => 'Экспорт данных определенных авторов',
                        'Authors' => 'Авторы',
                        'Exportdatabyspecificcategory' => 'Экспорт данных по определенной категории',
                        'Category' => 'Категория',
                        'ExportdatabasedonspecificInclusions' => 'Экспорт данных на основе определенных включений',
                        'DoyouwanttoSchedulethisExport' => 'Вы хотите запланировать этот экспорт?',
                        'SelectTimeZone' => 'Выберите часовой пояс',
                        'ScheduleExport' => 'График экспорта',
                        'DataExported' => 'Данные экспортированы',
                        'elementorfields'=>'Элементарные поля',
                        'License'=>'Лицензия',
                        'ThankYouForYourPurchase'=>'Спасибо за покупку',
                        'ToGetStartedYouNeedToActivateByEnteringTheLicensekey'=>'Для начала вам необходимо активировать, введя лицензионный ключ',
                        'EnterTheLicenseKey'=>'Введите лицензионный ключ',
                        'LicenseDetails'=>'Детали лицензии',
                        'ProductName'=>'наименование товара',
                        'LicenseKey'=>'Лицензионный ключ',
                        'NoDataFound'=>'Данные не найдены',
                        'Activate'=>'Активировать'
                );
        return $response;
        }
}