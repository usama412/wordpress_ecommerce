<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */

if (!defined('ABSPATH')) {
    exit;
}

return apply_filters('vehica/widgets', [
    /**
     * Layout elements
     */
    \Vehica\Widgets\WidgetCategory::LAYOUT => [
        \Vehica\Widgets\Layout\TemplateContentElement::class,
    ],
    /**
     * General elements
     */
    \Vehica\Widgets\WidgetCategory::GENERAL => [
        \Vehica\Widgets\General\ContactFormGeneralWidget::class,
        \Vehica\Widgets\General\MenuGeneralWidget::class,
        \Vehica\Widgets\General\SearchV1GeneralWidget::class,
        \Vehica\Widgets\General\SearchV2GeneralWidget::class,
        \Vehica\Widgets\General\SimpleMenuGeneralWidget::class,
        \Vehica\Widgets\General\SocialShareGeneralWidget::class,
        \Vehica\Widgets\General\PanelGeneralWidget::class,
        \Vehica\Widgets\General\BreadcrumbsGeneralWidget::class,
        \Vehica\Widgets\General\MapGeneralWidget::class,
        \Vehica\Widgets\General\LoginGeneralWidget::class,
        \Vehica\Widgets\General\LoginV2GeneralWidget::class,
        \Vehica\Widgets\General\RegisterGeneralWidget::class,
        \Vehica\Widgets\General\FeaturedCarsGeneralWidget::class,
        \Vehica\Widgets\General\CarTabsCarouselGeneralWidget::class,
        \Vehica\Widgets\General\UsersGeneralWidget::class,
        \Vehica\Widgets\General\TermCarouselGeneralWidget::class,
        \Vehica\Widgets\General\PhoneGeneralWidget::class,
        \Vehica\Widgets\General\EmailGeneralWidget::class,
        \Vehica\Widgets\General\AddressGeneralWidget::class,
        \Vehica\Widgets\General\ShortInfoGeneralWidget::class,
        \Vehica\Widgets\General\CopyrightsGeneralWidget::class,
        \Vehica\Widgets\General\SocialProfilesGeneralWidget::class,
        \Vehica\Widgets\General\LogoGeneralWidget::class,
        \Vehica\Widgets\General\LoanCalculatorGeneralWidget::class,
        \Vehica\Widgets\General\UsersV2GeneralWidget::class,
        \Vehica\Widgets\General\ComingSoonGeneralWidget::class,
        \Vehica\Widgets\General\RecentPostsGeneralWidget::class,
        \Vehica\Widgets\General\ImageCarouselGeneralWidget::class,
        \Vehica\Widgets\General\PostsGeneralWidget::class,
        \Vehica\Widgets\General\CarGridGeneralWidget::class,
        \Vehica\Widgets\General\CurrencySwitcherGeneralWidget::class,
        \Vehica\Widgets\General\CompareGeneralWidget::class,
        \Vehica\Widgets\General\SliderGeneralWidget::class,
        \Vehica\Widgets\General\ServicesGeneralWidget::class,
        \Vehica\Widgets\General\TestimonialCarouselGeneralWidget::class,
        \Vehica\Widgets\General\FeaturesGeneralWidget::class,
        \Vehica\Widgets\General\MapListingGeneralWidget::class,
        \Vehica\Widgets\General\SearchListingGeneralWidget::class,
    ],
    /**
     * Car (single)
     */
    \Vehica\Widgets\WidgetCategory::CAR_SINGLE => [
        \Vehica\Widgets\Car\Single\AttributesSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\RelatedCarListSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\RelatedCarCarouselSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\FeaturesSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\FeaturesV2SingleCarWidget::class,
        \Vehica\Widgets\Car\Single\IdSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\PhoneSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\AddToFavoriteSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\GallerySingleCarWidget::class,
        \Vehica\Widgets\Car\Single\GalleryV2SingleCarWidget::class,
        \Vehica\Widgets\Car\Single\GalleryV3SingleCarWidget::class,
        \Vehica\Widgets\Car\Single\GalleryV4SingleCarWidget::class,
        \Vehica\Widgets\Car\Single\DescriptionSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\NameSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\PriceSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\EmbedSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\TermsSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\UserNameSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\UserMailSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\UserSocialSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\UserRoleSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\UserImageSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\UserDisplayAddressSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\BigFeaturesSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\LocationSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\UserLocationSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\DateSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\ViewsSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\UserPhoneSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\CalculateLoanLinkSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\AddToCompareSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\NumberFieldSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\WhatsAppButtonSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\AttachmentsSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\ContactOwnerSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\UserDescriptionSingleCarWidget::class,
        \Vehica\Widgets\Car\Single\PrivateMessageSystemSingleCarWidget::class,
    ],
    /**
     * Car (archive)
     */
    \Vehica\Widgets\WidgetCategory::CAR_ARCHIVE => [
        \Vehica\Widgets\Car\Archive\SearchListingCarArchiveWidget::class,
    ],
    /**
     * Post (single)
     */
    \Vehica\Widgets\WidgetCategory::POST_SINGLE => [
        \Vehica\Widgets\Post\Single\CommentsSinglePostWidget::class,
        \Vehica\Widgets\Post\Single\RelatedPostsSinglePostWidget::class,
        \Vehica\Widgets\Post\Single\NameSinglePostWidget::class,
        \Vehica\Widgets\Post\Single\TextSinglePostWidget::class,
        \Vehica\Widgets\Post\Single\ImageSinglePostWidget::class,
        \Vehica\Widgets\Post\Single\DateSinglePostWidget::class,
        \Vehica\Widgets\Post\Single\TagsSinglePostWidget::class,
        \Vehica\Widgets\Post\Single\CategoriesSinglePostWidget::class,
        \Vehica\Widgets\Post\Single\AuthorNamePostSingleWidget::class,
        \Vehica\Widgets\Post\Single\AuthorImageSinglePostWidget::class,
        \Vehica\Widgets\Post\Single\CommentNumberSinglePostWidget::class,
    ],
    /**
     * Post (archive)
     */
    \Vehica\Widgets\WidgetCategory::POST_ARCHIVE => [
        \Vehica\Widgets\Post\Archive\NamePostArchiveWidget::class,
        \Vehica\Widgets\Post\Archive\PostsPostArchiveWidget::class,
        \Vehica\Widgets\Post\Archive\PostsV2PostArchiveWidget::class,
    ],
    /**
     * User
     */
    \Vehica\Widgets\WidgetCategory::USER => [
        \Vehica\Widgets\User\PhoneUserWidget::class,
        \Vehica\Widgets\User\EmailUserWidget::class,
        \Vehica\Widgets\User\NameUserWidget::class,
        \Vehica\Widgets\User\RoleUserWidget::class,
        \Vehica\Widgets\User\ImageUserWidget::class,
        \Vehica\Widgets\User\CarsUserWidget::class,
        \Vehica\Widgets\User\DisplayAddressUserWidget::class,
        \Vehica\Widgets\User\SocialUserWidget::class,
        \Vehica\Widgets\User\DescriptionUserWidget::class,
        \Vehica\Widgets\User\LocationUserWidget::class,
        \Vehica\Widgets\User\WhatsAppButtonUserWidget::class,
        \Vehica\Widgets\User\ContactOwnerUserWidget::class,
        \Vehica\Widgets\User\PrivateMessageSystemUserWidget::class,
    ]
]);