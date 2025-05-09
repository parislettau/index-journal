<?php

namespace Spatie\SchemaOrg;

use Spatie\SchemaOrg\Contracts\BroadcastServiceContract;
use Spatie\SchemaOrg\Contracts\IntangibleContract;
use Spatie\SchemaOrg\Contracts\RadioBroadcastServiceContract;
use Spatie\SchemaOrg\Contracts\ServiceContract;
use Spatie\SchemaOrg\Contracts\ThingContract;

/**
 * A delivery service through which radio content is provided via broadcast over
 * the air or online.
 *
 * @see https://schema.org/RadioBroadcastService
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2109
 *
 */
class RadioBroadcastService extends BaseType implements RadioBroadcastServiceContract, BroadcastServiceContract, IntangibleContract, ServiceContract, ThingContract
{
    /**
     * An additional type for the item, typically used for adding more specific
     * types from external vocabularies in microdata syntax. This is a
     * relationship between something and a class that the thing is in.
     * Typically the value is a URI-identified RDF class, and in this case
     * corresponds to the
     *     use of rdf:type in RDF. Text values can be used sparingly, for cases
     * where useful information can be added without their being an appropriate
     * schema to reference. In the case of text values, the class label should
     * follow the schema.org [style
     * guide](https://schema.org/docs/styleguide.html).
     *
     * @param string|string[] $additionalType
     *
     * @return static
     *
     * @see https://schema.org/additionalType
     */
    public function additionalType($additionalType)
    {
        return $this->setProperty('additionalType', $additionalType);
    }

    /**
     * The overall rating, based on a collection of reviews or ratings, of the
     * item.
     *
     * @param \Spatie\SchemaOrg\Contracts\AggregateRatingContract|\Spatie\SchemaOrg\Contracts\AggregateRatingContract[] $aggregateRating
     *
     * @return static
     *
     * @see https://schema.org/aggregateRating
     */
    public function aggregateRating($aggregateRating)
    {
        return $this->setProperty('aggregateRating', $aggregateRating);
    }

    /**
     * An alias for the item.
     *
     * @param string|string[] $alternateName
     *
     * @return static
     *
     * @see https://schema.org/alternateName
     */
    public function alternateName($alternateName)
    {
        return $this->setProperty('alternateName', $alternateName);
    }

    /**
     * The area within which users can expect to reach the broadcast service.
     *
     * @param \Spatie\SchemaOrg\Contracts\PlaceContract|\Spatie\SchemaOrg\Contracts\PlaceContract[] $area
     *
     * @return static
     *
     * @see https://schema.org/area
     */
    public function area($area)
    {
        return $this->setProperty('area', $area);
    }

    /**
     * The geographic area where a service or offered item is provided.
     *
     * @param \Spatie\SchemaOrg\Contracts\AdministrativeAreaContract|\Spatie\SchemaOrg\Contracts\AdministrativeAreaContract[]|\Spatie\SchemaOrg\Contracts\GeoShapeContract|\Spatie\SchemaOrg\Contracts\GeoShapeContract[]|\Spatie\SchemaOrg\Contracts\PlaceContract|\Spatie\SchemaOrg\Contracts\PlaceContract[]|string|string[] $areaServed
     *
     * @return static
     *
     * @see https://schema.org/areaServed
     */
    public function areaServed($areaServed)
    {
        return $this->setProperty('areaServed', $areaServed);
    }

    /**
     * An intended audience, i.e. a group for whom something was created.
     *
     * @param \Spatie\SchemaOrg\Contracts\AudienceContract|\Spatie\SchemaOrg\Contracts\AudienceContract[] $audience
     *
     * @return static
     *
     * @see https://schema.org/audience
     */
    public function audience($audience)
    {
        return $this->setProperty('audience', $audience);
    }

    /**
     * A means of accessing the service (e.g. a phone bank, a web site, a
     * location, etc.).
     *
     * @param \Spatie\SchemaOrg\Contracts\ServiceChannelContract|\Spatie\SchemaOrg\Contracts\ServiceChannelContract[] $availableChannel
     *
     * @return static
     *
     * @see https://schema.org/availableChannel
     */
    public function availableChannel($availableChannel)
    {
        return $this->setProperty('availableChannel', $availableChannel);
    }

    /**
     * An award won by or for this item.
     *
     * @param string|string[] $award
     *
     * @return static
     *
     * @see https://schema.org/award
     */
    public function award($award)
    {
        return $this->setProperty('award', $award);
    }

    /**
     * The brand(s) associated with a product or service, or the brand(s)
     * maintained by an organization or business person.
     *
     * @param \Spatie\SchemaOrg\Contracts\BrandContract|\Spatie\SchemaOrg\Contracts\BrandContract[]|\Spatie\SchemaOrg\Contracts\OrganizationContract|\Spatie\SchemaOrg\Contracts\OrganizationContract[] $brand
     *
     * @return static
     *
     * @see https://schema.org/brand
     */
    public function brand($brand)
    {
        return $this->setProperty('brand', $brand);
    }

    /**
     * The media network(s) whose content is broadcast on this station.
     *
     * @param \Spatie\SchemaOrg\Contracts\OrganizationContract|\Spatie\SchemaOrg\Contracts\OrganizationContract[] $broadcastAffiliateOf
     *
     * @return static
     *
     * @see https://schema.org/broadcastAffiliateOf
     */
    public function broadcastAffiliateOf($broadcastAffiliateOf)
    {
        return $this->setProperty('broadcastAffiliateOf', $broadcastAffiliateOf);
    }

    /**
     * The name displayed in the channel guide. For many US affiliates, it is
     * the network name.
     *
     * @param string|string[] $broadcastDisplayName
     *
     * @return static
     *
     * @see https://schema.org/broadcastDisplayName
     */
    public function broadcastDisplayName($broadcastDisplayName)
    {
        return $this->setProperty('broadcastDisplayName', $broadcastDisplayName);
    }

    /**
     * The frequency used for over-the-air broadcasts. Numeric values or simple
     * ranges, e.g. 87-99. In addition a shortcut idiom is supported for
     * frequencies of AM and FM radio channels, e.g. "87 FM".
     *
     * @param \Spatie\SchemaOrg\Contracts\BroadcastFrequencySpecificationContract|\Spatie\SchemaOrg\Contracts\BroadcastFrequencySpecificationContract[]|string|string[] $broadcastFrequency
     *
     * @return static
     *
     * @see https://schema.org/broadcastFrequency
     * @link https://github.com/schemaorg/schemaorg/issues/1004
     */
    public function broadcastFrequency($broadcastFrequency)
    {
        return $this->setProperty('broadcastFrequency', $broadcastFrequency);
    }

    /**
     * The timezone in [ISO 8601 format](http://en.wikipedia.org/wiki/ISO_8601)
     * for which the service bases its broadcasts.
     *
     * @param string|string[] $broadcastTimezone
     *
     * @return static
     *
     * @see https://schema.org/broadcastTimezone
     */
    public function broadcastTimezone($broadcastTimezone)
    {
        return $this->setProperty('broadcastTimezone', $broadcastTimezone);
    }

    /**
     * The organization owning or operating the broadcast service.
     *
     * @param \Spatie\SchemaOrg\Contracts\OrganizationContract|\Spatie\SchemaOrg\Contracts\OrganizationContract[] $broadcaster
     *
     * @return static
     *
     * @see https://schema.org/broadcaster
     */
    public function broadcaster($broadcaster)
    {
        return $this->setProperty('broadcaster', $broadcaster);
    }

    /**
     * An entity that arranges for an exchange between a buyer and a seller.  In
     * most cases a broker never acquires or releases ownership of a product or
     * service involved in an exchange.  If it is not clear whether an entity is
     * a broker, seller, or buyer, the latter two terms are preferred.
     *
     * @param \Spatie\SchemaOrg\Contracts\OrganizationContract|\Spatie\SchemaOrg\Contracts\OrganizationContract[]|\Spatie\SchemaOrg\Contracts\PersonContract|\Spatie\SchemaOrg\Contracts\PersonContract[] $broker
     *
     * @return static
     *
     * @see https://schema.org/broker
     */
    public function broker($broker)
    {
        return $this->setProperty('broker', $broker);
    }

    /**
     * A [callsign](https://en.wikipedia.org/wiki/Call_sign), as used in
     * broadcasting and radio communications to identify people, radio and TV
     * stations, or vehicles.
     *
     * @param string|string[] $callSign
     *
     * @return static
     *
     * @see https://schema.org/callSign
     * @see https://pending.schema.org
     * @link https://github.com/schemaorg/schemaorg/issues/2109
     */
    public function callSign($callSign)
    {
        return $this->setProperty('callSign', $callSign);
    }

    /**
     * A category for the item. Greater signs or slashes can be used to
     * informally indicate a category hierarchy.
     *
     * @param \Spatie\SchemaOrg\Contracts\CategoryCodeContract|\Spatie\SchemaOrg\Contracts\CategoryCodeContract[]|\Spatie\SchemaOrg\Contracts\PhysicalActivityCategoryContract|\Spatie\SchemaOrg\Contracts\PhysicalActivityCategoryContract[]|\Spatie\SchemaOrg\Contracts\ThingContract|\Spatie\SchemaOrg\Contracts\ThingContract[]|string|string[] $category
     *
     * @return static
     *
     * @see https://schema.org/category
     */
    public function category($category)
    {
        return $this->setProperty('category', $category);
    }

    /**
     * A description of the item.
     *
     * @param \Spatie\SchemaOrg\Contracts\TextObjectContract|\Spatie\SchemaOrg\Contracts\TextObjectContract[]|string|string[] $description
     *
     * @return static
     *
     * @see https://schema.org/description
     */
    public function description($description)
    {
        return $this->setProperty('description', $description);
    }

    /**
     * A sub property of description. A short description of the item used to
     * disambiguate from other, similar items. Information from other properties
     * (in particular, name) may be necessary for the description to be useful
     * for disambiguation.
     *
     * @param string|string[] $disambiguatingDescription
     *
     * @return static
     *
     * @see https://schema.org/disambiguatingDescription
     */
    public function disambiguatingDescription($disambiguatingDescription)
    {
        return $this->setProperty('disambiguatingDescription', $disambiguatingDescription);
    }

    /**
     * A broadcast channel of a broadcast service.
     *
     * @param \Spatie\SchemaOrg\Contracts\BroadcastChannelContract|\Spatie\SchemaOrg\Contracts\BroadcastChannelContract[] $hasBroadcastChannel
     *
     * @return static
     *
     * @see https://schema.org/hasBroadcastChannel
     * @link https://github.com/schemaorg/schemaorg/issues/1004
     */
    public function hasBroadcastChannel($hasBroadcastChannel)
    {
        return $this->setProperty('hasBroadcastChannel', $hasBroadcastChannel);
    }

    /**
     * Certification information about a product, organization, service, place,
     * or person.
     *
     * @param \Spatie\SchemaOrg\Contracts\CertificationContract|\Spatie\SchemaOrg\Contracts\CertificationContract[] $hasCertification
     *
     * @return static
     *
     * @see https://schema.org/hasCertification
     * @link https://github.com/schemaorg/schemaorg/issues/3230
     */
    public function hasCertification($hasCertification)
    {
        return $this->setProperty('hasCertification', $hasCertification);
    }

    /**
     * Indicates an OfferCatalog listing for this Organization, Person, or
     * Service.
     *
     * @param \Spatie\SchemaOrg\Contracts\OfferCatalogContract|\Spatie\SchemaOrg\Contracts\OfferCatalogContract[] $hasOfferCatalog
     *
     * @return static
     *
     * @see https://schema.org/hasOfferCatalog
     */
    public function hasOfferCatalog($hasOfferCatalog)
    {
        return $this->setProperty('hasOfferCatalog', $hasOfferCatalog);
    }

    /**
     * The hours during which this service or contact is available.
     *
     * @param \Spatie\SchemaOrg\Contracts\OpeningHoursSpecificationContract|\Spatie\SchemaOrg\Contracts\OpeningHoursSpecificationContract[] $hoursAvailable
     *
     * @return static
     *
     * @see https://schema.org/hoursAvailable
     */
    public function hoursAvailable($hoursAvailable)
    {
        return $this->setProperty('hoursAvailable', $hoursAvailable);
    }

    /**
     * The identifier property represents any kind of identifier for any kind of
     * [[Thing]], such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides
     * dedicated properties for representing many of these, either as textual
     * strings or as URL (URI) links. See [background
     * notes](/docs/datamodel.html#identifierBg) for more details.
     *
     * @param \Spatie\SchemaOrg\Contracts\PropertyValueContract|\Spatie\SchemaOrg\Contracts\PropertyValueContract[]|string|string[] $identifier
     *
     * @return static
     *
     * @see https://schema.org/identifier
     */
    public function identifier($identifier)
    {
        return $this->setProperty('identifier', $identifier);
    }

    /**
     * An image of the item. This can be a [[URL]] or a fully described
     * [[ImageObject]].
     *
     * @param \Spatie\SchemaOrg\Contracts\ImageObjectContract|\Spatie\SchemaOrg\Contracts\ImageObjectContract[]|string|string[] $image
     *
     * @return static
     *
     * @see https://schema.org/image
     */
    public function image($image)
    {
        return $this->setProperty('image', $image);
    }

    /**
     * The language of the content or performance or used in an action. Please
     * use one of the language codes from the [IETF BCP 47
     * standard](http://tools.ietf.org/html/bcp47). See also
     * [[availableLanguage]].
     *
     * @param \Spatie\SchemaOrg\Contracts\LanguageContract|\Spatie\SchemaOrg\Contracts\LanguageContract[]|string|string[] $inLanguage
     *
     * @return static
     *
     * @see https://schema.org/inLanguage
     * @link https://github.com/schemaorg/schemaorg/issues/2382
     */
    public function inLanguage($inLanguage)
    {
        return $this->setProperty('inLanguage', $inLanguage);
    }

    /**
     * A pointer to another, somehow related product (or multiple products).
     *
     * @param \Spatie\SchemaOrg\Contracts\ProductContract|\Spatie\SchemaOrg\Contracts\ProductContract[]|\Spatie\SchemaOrg\Contracts\ServiceContract|\Spatie\SchemaOrg\Contracts\ServiceContract[] $isRelatedTo
     *
     * @return static
     *
     * @see https://schema.org/isRelatedTo
     */
    public function isRelatedTo($isRelatedTo)
    {
        return $this->setProperty('isRelatedTo', $isRelatedTo);
    }

    /**
     * A pointer to another, functionally similar product (or multiple
     * products).
     *
     * @param \Spatie\SchemaOrg\Contracts\ProductContract|\Spatie\SchemaOrg\Contracts\ProductContract[]|\Spatie\SchemaOrg\Contracts\ServiceContract|\Spatie\SchemaOrg\Contracts\ServiceContract[] $isSimilarTo
     *
     * @return static
     *
     * @see https://schema.org/isSimilarTo
     */
    public function isSimilarTo($isSimilarTo)
    {
        return $this->setProperty('isSimilarTo', $isSimilarTo);
    }

    /**
     * An associated logo.
     *
     * @param \Spatie\SchemaOrg\Contracts\ImageObjectContract|\Spatie\SchemaOrg\Contracts\ImageObjectContract[]|string|string[] $logo
     *
     * @return static
     *
     * @see https://schema.org/logo
     */
    public function logo($logo)
    {
        return $this->setProperty('logo', $logo);
    }

    /**
     * Indicates a page (or other CreativeWork) for which this thing is the main
     * entity being described. See [background
     * notes](/docs/datamodel.html#mainEntityBackground) for details.
     *
     * @param \Spatie\SchemaOrg\Contracts\CreativeWorkContract|\Spatie\SchemaOrg\Contracts\CreativeWorkContract[]|string|string[] $mainEntityOfPage
     *
     * @return static
     *
     * @see https://schema.org/mainEntityOfPage
     */
    public function mainEntityOfPage($mainEntityOfPage)
    {
        return $this->setProperty('mainEntityOfPage', $mainEntityOfPage);
    }

    /**
     * The name of the item.
     *
     * @param string|string[] $name
     *
     * @return static
     *
     * @see https://schema.org/name
     */
    public function name($name)
    {
        return $this->setProperty('name', $name);
    }

    /**
     * An offer to provide this item&#x2014;for example, an offer to sell a
     * product, rent the DVD of a movie, perform a service, or give away tickets
     * to an event. Use [[businessFunction]] to indicate the kind of transaction
     * offered, i.e. sell, lease, etc. This property can also be used to
     * describe a [[Demand]]. While this property is listed as expected on a
     * number of common types, it can be used in others. In that case, using a
     * second type, such as Product or a subtype of Product, can clarify the
     * nature of the offer.
     *
     * @param \Spatie\SchemaOrg\Contracts\DemandContract|\Spatie\SchemaOrg\Contracts\DemandContract[]|\Spatie\SchemaOrg\Contracts\OfferContract|\Spatie\SchemaOrg\Contracts\OfferContract[] $offers
     *
     * @return static
     *
     * @see https://schema.org/offers
     * @link https://github.com/schemaorg/schemaorg/issues/2289
     */
    public function offers($offers)
    {
        return $this->setProperty('offers', $offers);
    }

    /**
     * A broadcast service to which the broadcast service may belong to such as
     * regional variations of a national channel.
     *
     * @param \Spatie\SchemaOrg\Contracts\BroadcastServiceContract|\Spatie\SchemaOrg\Contracts\BroadcastServiceContract[] $parentService
     *
     * @return static
     *
     * @see https://schema.org/parentService
     */
    public function parentService($parentService)
    {
        return $this->setProperty('parentService', $parentService);
    }

    /**
     * Indicates a potential Action, which describes an idealized action in
     * which this thing would play an 'object' role.
     *
     * @param \Spatie\SchemaOrg\Contracts\ActionContract|\Spatie\SchemaOrg\Contracts\ActionContract[] $potentialAction
     *
     * @return static
     *
     * @see https://schema.org/potentialAction
     */
    public function potentialAction($potentialAction)
    {
        return $this->setProperty('potentialAction', $potentialAction);
    }

    /**
     * The tangible thing generated by the service, e.g. a passport, permit,
     * etc.
     *
     * @param \Spatie\SchemaOrg\Contracts\ThingContract|\Spatie\SchemaOrg\Contracts\ThingContract[] $produces
     *
     * @return static
     *
     * @see https://schema.org/produces
     */
    public function produces($produces)
    {
        return $this->setProperty('produces', $produces);
    }

    /**
     * The service provider, service operator, or service performer; the goods
     * producer. Another party (a seller) may offer those services or goods on
     * behalf of the provider. A provider may also serve as the seller.
     *
     * @param \Spatie\SchemaOrg\Contracts\OrganizationContract|\Spatie\SchemaOrg\Contracts\OrganizationContract[]|\Spatie\SchemaOrg\Contracts\PersonContract|\Spatie\SchemaOrg\Contracts\PersonContract[] $provider
     *
     * @return static
     *
     * @see https://schema.org/provider
     * @see https://pending.schema.org
     */
    public function provider($provider)
    {
        return $this->setProperty('provider', $provider);
    }

    /**
     * Indicates the mobility of a provided service (e.g. 'static', 'dynamic').
     *
     * @param string|string[] $providerMobility
     *
     * @return static
     *
     * @see https://schema.org/providerMobility
     */
    public function providerMobility($providerMobility)
    {
        return $this->setProperty('providerMobility', $providerMobility);
    }

    /**
     * A review of the item.
     *
     * @param \Spatie\SchemaOrg\Contracts\ReviewContract|\Spatie\SchemaOrg\Contracts\ReviewContract[] $review
     *
     * @return static
     *
     * @see https://schema.org/review
     */
    public function review($review)
    {
        return $this->setProperty('review', $review);
    }

    /**
     * URL of a reference Web page that unambiguously indicates the item's
     * identity. E.g. the URL of the item's Wikipedia page, Wikidata entry, or
     * official website.
     *
     * @param string|string[] $sameAs
     *
     * @return static
     *
     * @see https://schema.org/sameAs
     */
    public function sameAs($sameAs)
    {
        return $this->setProperty('sameAs', $sameAs);
    }

    /**
     * The geographic area where the service is provided.
     *
     * @param \Spatie\SchemaOrg\Contracts\AdministrativeAreaContract|\Spatie\SchemaOrg\Contracts\AdministrativeAreaContract[]|\Spatie\SchemaOrg\Contracts\GeoShapeContract|\Spatie\SchemaOrg\Contracts\GeoShapeContract[]|\Spatie\SchemaOrg\Contracts\PlaceContract|\Spatie\SchemaOrg\Contracts\PlaceContract[] $serviceArea
     *
     * @return static
     *
     * @see https://schema.org/serviceArea
     */
    public function serviceArea($serviceArea)
    {
        return $this->setProperty('serviceArea', $serviceArea);
    }

    /**
     * The audience eligible for this service.
     *
     * @param \Spatie\SchemaOrg\Contracts\AudienceContract|\Spatie\SchemaOrg\Contracts\AudienceContract[] $serviceAudience
     *
     * @return static
     *
     * @see https://schema.org/serviceAudience
     */
    public function serviceAudience($serviceAudience)
    {
        return $this->setProperty('serviceAudience', $serviceAudience);
    }

    /**
     * The tangible thing generated by the service, e.g. a passport, permit,
     * etc.
     *
     * @param \Spatie\SchemaOrg\Contracts\ThingContract|\Spatie\SchemaOrg\Contracts\ThingContract[] $serviceOutput
     *
     * @return static
     *
     * @see https://schema.org/serviceOutput
     */
    public function serviceOutput($serviceOutput)
    {
        return $this->setProperty('serviceOutput', $serviceOutput);
    }

    /**
     * The type of service being offered, e.g. veterans' benefits, emergency
     * relief, etc.
     *
     * @param \Spatie\SchemaOrg\Contracts\GovernmentBenefitsTypeContract|\Spatie\SchemaOrg\Contracts\GovernmentBenefitsTypeContract[]|string|string[] $serviceType
     *
     * @return static
     *
     * @see https://schema.org/serviceType
     */
    public function serviceType($serviceType)
    {
        return $this->setProperty('serviceType', $serviceType);
    }

    /**
     * A slogan or motto associated with the item.
     *
     * @param string|string[] $slogan
     *
     * @return static
     *
     * @see https://schema.org/slogan
     */
    public function slogan($slogan)
    {
        return $this->setProperty('slogan', $slogan);
    }

    /**
     * A CreativeWork or Event about this Thing.
     *
     * @param \Spatie\SchemaOrg\Contracts\CreativeWorkContract|\Spatie\SchemaOrg\Contracts\CreativeWorkContract[]|\Spatie\SchemaOrg\Contracts\EventContract|\Spatie\SchemaOrg\Contracts\EventContract[] $subjectOf
     *
     * @return static
     *
     * @see https://schema.org/subjectOf
     * @link https://github.com/schemaorg/schemaorg/issues/1670
     */
    public function subjectOf($subjectOf)
    {
        return $this->setProperty('subjectOf', $subjectOf);
    }

    /**
     * Human-readable terms of service documentation.
     *
     * @param string|string[] $termsOfService
     *
     * @return static
     *
     * @see https://schema.org/termsOfService
     * @see https://pending.schema.org
     * @link https://github.com/schemaorg/schemaorg/issues/1423
     */
    public function termsOfService($termsOfService)
    {
        return $this->setProperty('termsOfService', $termsOfService);
    }

    /**
     * URL of the item.
     *
     * @param string|string[] $url
     *
     * @return static
     *
     * @see https://schema.org/url
     */
    public function url($url)
    {
        return $this->setProperty('url', $url);
    }

    /**
     * The type of screening or video broadcast used (e.g. IMAX, 3D, SD, HD,
     * etc.).
     *
     * @param string|string[] $videoFormat
     *
     * @return static
     *
     * @see https://schema.org/videoFormat
     */
    public function videoFormat($videoFormat)
    {
        return $this->setProperty('videoFormat', $videoFormat);
    }
}
