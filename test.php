<?php

declare(strict_types=1);

require 'vendor/autoload.php'; // Include Composer autoloader

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

/**
 * Define interfaces (empty for this example)
 */
interface MiuzElasticProductInterface {}
interface ElasticProductInterface {}
interface BaseElementInterface {}
interface BaseIblockElementInterface {}

/**
 * BaseIblockElement class
 */
class BaseIblockElement implements BaseIblockElementInterface
{
    /**
     * @Groups({"list"})
     */
    protected int $id;

    /**
     * @SerializedName("iblock_id")
     */
    protected int $iblockId;

    /**
     * @Groups({"list"})
     */
    protected string $name;

    /**
     * @Groups({"list"})
     */
    protected string $code;

    /**
     * @SerializedName("xml_id")
     */
    protected string $xmlId;

    #[SerializedName('date_create')]
    protected string $dateCreate;

    #[SerializedName('timestamp_x')]
    protected string $timestampX;

    protected bool $active;
    protected ?int $sort = null;

    /**
     * @SerializedName("detail_page_url")
     */
    protected string $detailPageUrl;

    protected ?string $detailText = null;

    /**
     * @var array<int>
     * @SerializedName("parent_sections")
     */
    protected array $parentSections = [];

    /**
     * @var Section[]
     */
    protected array $section = [];

    /** @var array<string, mixed> */
    protected array $props = [];

    // Getters and setters for all properties
    // For brevity, only a few are shown here

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIblockId(): int
    {
        return $this->iblockId;
    }

    public function setIblockId(int $iblockId): void
    {
        $this->iblockId = $iblockId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getXmlId(): string
    {
        return $this->xmlId;
    }

    public function setXmlId(string $xmlId): void
    {
        $this->xmlId = $xmlId;
    }

    /**
     * @return string
     */
    public function getDateCreate(): string
    {
        return $this->dateCreate;
    }

    /**
     * @param string $dateCreate
     */
    public function setDateCreate(string $dateCreate): void
    {
        $this->dateCreate = $dateCreate;
    }

    /**
     * @return string
     */
    public function getTimestampX(): string
    {
        return $this->timestampX;
    }

    /**
     * @param string $timestampX
     */
    public function setTimestampX(string $timestampX): void
    {
        $this->timestampX = $timestampX;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(?int $sort): void
    {
        $this->sort = $sort;
    }

    public function getDetailPageUrl(): string
    {
        return $this->detailPageUrl;
    }

    public function setDetailPageUrl(string $detailPageUrl): void
    {
        $this->detailPageUrl = $detailPageUrl;
    }

    public function getParentSections(): array
    {
        return $this->parentSections;
    }

    public function setParentSections(array $parentSections): void
    {
        $this->parentSections = $parentSections;
    }

    /**
     * @return Section[]
     */
    public function getSection(): array
    {
        return $this->section;
    }

    /**
     * @param Section[] $section
     */
    public function setSection(array $section): void
    {
        $this->section = $section;
    }

    public function getProps(): array
    {
        return $this->props;
    }

    public function setProps(array $props): void
    {
        $this->props = $props;
    }

    // Continue with getters and setters for all other properties...
}

/**
 * BaseElement class
 */
class BaseElement extends BaseIblockElement implements BaseElementInterface
{
    /** @var array<string, int> */
    protected array $prices = [];

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function setPrices(array $prices): void
    {
        $this->prices = $prices;
    }
}

/**
 * Product class
 */
class Product extends BaseElement implements MiuzElasticProductInterface
{
    /** @var array<string, int>|null */
    protected ?array $discounts = null;

    /**
     * @var ColorGroup[]|null
     * @SerializedName("color_groups")
     */
    protected ?array $colorGroups = null;

    /**
     * @var array<string, array<string, float>>|null
     */
    protected ?array $cityPrices = null;

    /** @var Offer[]|null */
    protected ?array $offers = null;

    // Getters and setters

    public function getDiscounts(): ?array
    {
        return $this->discounts;
    }

    public function setDiscounts(?array $discounts): void
    {
        $this->discounts = $discounts;
    }

    /**
     * @return ColorGroup[]|null
     */
    public function getColorGroups(): ?array
    {
        return $this->colorGroups;
    }

    /**
     * @param ColorGroup[]|null $colorGroups
     */
    public function setColorGroups(?array $colorGroups): void
    {
        $this->colorGroups = $colorGroups;
    }

    public function getCityPrices(): ?array
    {
        return $this->cityPrices;
    }

    public function setCityPrices(?array $cityPrices): void
    {
        $this->cityPrices = $cityPrices;
    }

    /**
     * @return Offer[]|null
     */
    public function getOffers(): ?array
    {
        return $this->offers;
    }

    /**
     * @param Offer[]|null $offers
     */
    public function setOffers(?array $offers): void
    {
        $this->offers = $offers;
    }

    // Continue with getters and setters for all other properties...
}

/**
 * Offer class (empty for this example)
 */
class Offer
{
    // Implement properties and methods as needed
}

/**
 * Section class
 */
class Section
{
    protected int $id;
    protected string $name;
    protected string $code;

    // Getters and setters

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}

/**
 * ColorGroup class
 */
class ColorGroup
{
    protected string $color;

    /**
     * @SerializedName("color_id")
     */
    protected string $colorId;

    /**
     * @SerializedName("color_code")
     */
    protected string $colorCode;

    protected array $sizes;
    protected array $prices;

    /**
     * @SerializedName("old_price")
     */
    protected $oldPrice;

    /**
     * @SerializedName("price_variable")
     */
    protected bool $priceVariable;

    // Getters and setters

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function getColorId(): string
    {
        return $this->colorId;
    }

    public function setColorId(string $colorId): void
    {
        $this->colorId = $colorId;
    }

    public function getColorCode(): string
    {
        return $this->colorCode;
    }

    public function setColorCode(string $colorCode): void
    {
        $this->colorCode = $colorCode;
    }

    public function getSizes(): array
    {
        return $this->sizes;
    }

    public function setSizes(array $sizes): void
    {
        $this->sizes = $sizes;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function setPrices(array $prices): void
    {
        $this->prices = $prices;
    }

    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    public function setOldPrice($oldPrice): void
    {
        $this->oldPrice = $oldPrice;
    }

    public function isPriceVariable(): bool
    {
        return $this->priceVariable;
    }

    public function setPriceVariable(bool $priceVariable): void
    {
        $this->priceVariable = $priceVariable;
    }

    // Continue with getters and setters for all other properties...
}

/**
 * Main script
 */

// Parse command-line arguments
$options = getopt("", ["count:", "num-products:"]);
$count = isset($options['count']) ? intval($options['count']) : 1;
$numProducts = isset($options['num-products']) ? intval($options['num-products']) : 1;

// Generate sample data
function generateSampleProduct(int $id): array
{
    return [
        // Adjusted data to closely match your original JSON example
        // Increment 'id' and modify 'code' and 'xml_id' to simulate multiple products
        "id" => 1867555 + $id,
        "name" => "Кольцо с сапфиром, сапфирами и фианитами Артикул: R4150-C-6119S",
        "code" => "R4150-C-6119S-" . $id,
        "xml_id" => "R4150-C-6119S-" . $id,
        "iblock_id" => 12,
        "date_create" => "2016-04-23T18:17:36",
        "timestamp_x" => "2024-09-21T22:30:23",
        "active" => true,
        "sort" => 500,
        "detail_page_url" => "/catalog/rings/R4150-C-6119S/",
        "parent_sections" => [
            383
        ],
        "section" => [
            [
                "id" => 383,
                "name" => "Кольца",
                "code" => "rings"
            ]
        ],
        "props" => [
            "article" => "R4150-C-6119S",
            "stores" => [],
            "supplaer" => [],
            // ... Include all other properties as in your JSON example
            "collection" => [
                [
                    "id" => 665,
                    "code" => "aktsiya-l-occitane-10112023",
                    "name" => "Акция L'Occitane_10112023"
                ]
            ],
            // ... Continue with other properties
            "labels" => [
                [
                    "id" => 1,
                    "name" => "NEW",
                    "sort" => 100,
                    "text_color" => "#FFFFFF",
                    "bg_color" => "#202021"
                ]
            ],
            // ... Continue with other properties
            "for_whom" => [
                "id" => 3,
                "xml_id" => "u",
                "name" => "Универсальное"
            ],
            // ... Continue with other properties
            "is_reserve" => false
        ],
        "prices" => [
            "price_1" => 47900,
            "price_4" => 16765,
            "price_5" => 16765,
            "price_6" => 16765,
            "price_7" => 15807
        ],
        "color_groups" => [
            [
                "color" => "Красный",
                "color_id" => "R",
                "color_code" => "#811e3c",
                "sizes" => [
                    16.5
                ],
                "prices" => [
                    "price_1" => 47900,
                    "price_4" => 16765,
                    "price_5" => 16765,
                    "price_6" => 16765,
                    "price_7" => 15807
                ],
                "old_price" => 47900,
                "price_variable" => true
            ],
            [
                "color" => "Белый",
                "color_id" => "W",
                "color_code" => "#ffffff",
                "sizes" => [
                    16
                ],
                "prices" => [
                    "price_1" => 49900,
                    "price_4" => 17465,
                    "price_5" => 17465,
                    "price_6" => 17465,
                    "price_7" => 16467
                ],
                "old_price" => 49900,
                "price_variable" => false
            ]
        ],
        // Include other properties like "prices_3405", "offers", etc.
        "offers" => null
    ];
}

function generateSampleData(int $numProducts): array
{
    $data = [];
    for ($i = 1; $i <= $numProducts; $i++) {
        $data[] = generateSampleProduct($i);
    }
    return $data;
}

// Create the serializer with annotation support

$encoders = [new XmlEncoder(), new JsonEncoder()];
$normalizers = [new DateTimeNormalizer(), new ArrayDenormalizer(), new ObjectNormalizer()];
$serializer = new SymfonySerializer($normalizers, $encoders);

// Prepare the data to denormalize
$data = generateSampleData($numProducts);

$jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);

$totalTime = 0.0;
$numIterations = 0;

if ($count === 0) {
    // Infinite loop - limit to 60 seconds for safety
    $endTime = time() + 60; // Run for 60 seconds
    while (time() < $endTime) {
        $startTime = microtime(true);
        $items = json_decode($jsonData, true);
        try {
            /** @var Product[] $products */
            $products = $serializer->denormalize($items, 'Product[]');
        } catch (NotNormalizableValueException $e) {
            echo "Denormalization error: " . $e->getMessage() . "\n";
            exit(1);
        }
        $iterationTime = microtime(true) - $startTime;
        $totalTime += $iterationTime;
        $numIterations++;
    }
} else {
    for ($i = 0; $i < $count; $i++) {
        $startTime = microtime(true);
        $items = json_decode($jsonData, true);
        try {
            /** @var Product[] $products */
            $products = $serializer->denormalize($items, 'Product[]');
        } catch (NotNormalizableValueException $e) {
            echo "Denormalization error: " . $e->getMessage() . "\n";
            exit(1);
        }
        $iterationTime = microtime(true) - $startTime;
        $totalTime += $iterationTime;
        $numIterations++;
    }
}

$averageTime = $totalTime / $numIterations;

echo "Total time: " . $totalTime . " seconds\n";
echo "Number of denormalizations: " . $numIterations . "\n";
echo "Number of products per denormalization: " . $numProducts . "\n";
echo "Average time per denormalization: " . $averageTime . " seconds\n";
