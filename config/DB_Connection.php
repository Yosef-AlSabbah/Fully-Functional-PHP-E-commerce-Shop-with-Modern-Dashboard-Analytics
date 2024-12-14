<?php
class DB_Connection
{
    //Singelton Design Pattern
    private static $instance;

    private function __construct()
    {
    }

    private const DB_HOST = "localhost", DB_USER = "root", DB_PASSWORD = "", DB_NAME = "hypex";

    public static function getInstance(): mysqli
    {
        if (self::$instance == null) {
            try {
                self::$instance = new MySQLi(SELF::DB_HOST, SELF::DB_USER, SELF::DB_PASSWORD, SELF::DB_NAME);
            } catch (mysqli_sql_exception) {
                echo ("<script>window.location.href='/HYPEX/503/503.html';</script>");
                exit;
            }
        }
        return self::$instance;
    }



    //==================================<( Stores )>==================================\\

    public static function getStores(): mysqli_result|bool
    {
        return SELF::getInstance()->query("SELECT `ID`, `store_name`, `Image`, `Phone`, `Address`, `category_id`,`Category_name` FROM `stores` INNER JOIN `category` USING(`category_id`) ORDER BY `ID` ASC;");
    }

    public static function getStoresInfo(&$postStoreSearch, &$getCategoryId, int $show_from_row, $number_of_rows_per_page): mysqli_result|false
    {
        // SELF::secureInput($show_from_row, $number_of_rows_per_page);
        SELF::clearResults();
        $stmt = SELF::getInstance()->prepare("CALL GetStoreDetails( ? , ? , ? , ? );");
        $storeSearch = ($postStoreSearch ?? NULL);
        $categoryId = ($getCategoryId ?? NULL);
        $stmt->bind_param("siii", $storeSearch, $categoryId, $show_from_row, $number_of_rows_per_page);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function getStoresInfoById(&$storeId): mysqli_result|false
    {
        // SELF::secureInput($storeId);
        SELF::clearResults();
        $stmt = SELF::getInstance()->prepare("CALL GetStoreDetailById( ? );");
        $stmt->bind_param("i", $storeId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function getUserStoreRating(int $storeId): mysqli_result|false
    {
        // SELF::secureInput($storeId);
        $stmt = SELF::getInstance()->prepare("SELECT `rating`, `rating_id` FROM `ratings` WHERE `user_ip` = '{$_SERVER['REMOTE_ADDR']}' AND `store_id` = ? ;");
        $stmt->bind_param("i", $storeId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function getStoreById(int $storeId): array|false|null
    {
        // SELF::secureInput($storeId);
        $stmt = DB_Connection::getInstance()->prepare("SELECT `Image`, `Phone`, `Address`, `category_id`, `store_name` FROM `stores` WHERE `ID` = ? ;");
        $stmt->bind_param("i", $storeId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function getTotalNumberStores(): int
    {
        return SELF::getInstance()->query("SELECT COUNT(*) FROM `stores`;")->fetch_array()[0];
    }

    public static function getStoreRatingTrending(string $interval, int $maxNumOfResults): mysqli_result|false
    {
        // SELF::secureInput($interval, $maxNumOfResults);
        SELF::clearResults();
        $stmt = SELF::getInstance()->prepare("CALL storesRatingTrend( DATE_SUB(now(), INTERVAL $interval) , ? );");
        $stmt->bind_param("i", $maxNumOfResults);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function addStore(string $imgPath, string $storePhoneNumber, string $storeAddress, int $categoryId, string $storeName): bool
    {
        // SELF::secureInput($imgPath, $storePhoneNumber, $storeAddress, $categoryId, $storeName);
        $stmt = SELF::getInstance()->prepare("INSERT INTO `stores`(`Image`, `Phone`, `Address`, `category_id`, `store_name`) VALUES ( ? , ? , ? , ? , ? );");
        $stmt->bind_param("sssis", $imgPath, $storePhoneNumber, $storeAddress, $categoryId, $storeName);
        return $stmt->execute();
    }

    public static function deleteStore(int $storeId)
    {
        unlink(SELF::getStoreById($storeId)['Image']); // delete image
        // SELF::secureInput($storeId);
        $stmt = SELF::getInstance()->prepare("DELETE FROM `stores` WHERE `ID` = ? ;");
        $stmt->bind_param("i", $storeId);
        return $stmt->execute();
    }

    public static function updateStore(int $storeId, $imgPath, string $storePhoneNumber, string $storeAddress, int $categoryId, string $storeName): bool
    {
        // SELF::secureInput($storeId, $imgPath, $storePhoneNumber, $storeAddress, $categoryId, $storeName);
        $dynamic =  (!empty($imgPath) ? "`Image`= ? ," : " ");
        $stmt = SELF::getInstance()->prepare("UPDATE `stores` SET $dynamic `Phone`= ? ,`Address`= ? ,`category_id`= ? ,`store_name`= ? WHERE `ID` = $storeId;");
        if (empty($imgPath))
            $stmt->bind_param("ssis", $storePhoneNumber, $storeAddress, $categoryId, $storeName);
        else
            $stmt->bind_param("sssis", $imgPath, $storePhoneNumber, $storeAddress, $categoryId, $storeName);
        return $stmt->execute();
    }




    //==================================<( Categories )>==================================\\

    public static function getCategoriesWithStoreCount(): mysqli_result|bool
    {
        SELF::clearResults();
        return SELF::getInstance()->query("CALL getCategoriesWithStoreCount();");
    }

    public static function getCategoryName(int $categoryId): mysqli_result|false
    {
        $stmt = SELF::getInstance()->prepare("SELECT `Category_name` FROM `category` WHERE `category_id` = ?;");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function getCategoriesASC(): mysqli_result|bool
    {
        return SELF::getInstance()->query("SELECT `category_id`, `Category_name` FROM `category` ORDER BY `category_id` ASC;");
    }

    public static function addNewCategory(string $categoryName): bool
    {
        // SELF::secureInput($categoryName);
        $stmt = SELF::getInstance()->prepare("INSERT INTO `category`(`Category_name`) VALUES ( ? );");
        $stmt->bind_param("s", $categoryName);
        return $stmt->execute();
    }

    public static function getTotalNumberCategories()
    {
        return SELF::getInstance()->query("SELECT COUNT(`category_id`) FROM `category`;")->fetch_array()[0];
    }

    public static function deleteCategory(int $categoryId): bool
    {
        // SELF::secureInput($categoryId);
        $stmt = SELF::getInstance()->prepare("DELETE FROM `category` WHERE `category_id` = ? ;");
        $stmt->bind_param("i", $categoryId);
        return $stmt->execute();
    }

    public static function updateCategory(string $categoryName, int $categoryId): bool
    {
        // SELF::secureInput($categoryName, $categoryId);
        $stmt = SELF::getInstance()->prepare("UPDATE `category` SET `Category_name`= ? WHERE `category_id` = ? ;");
        $stmt->bind_param("si", trim($categoryName), $categoryId);
        return $stmt->execute();
    }



    //==================================<( Ratings )>==================================\\

    public static function addOrUpdateRating(int $storeId, int $rating): bool
    {
        // SELF::secureInput($storeId, $rating);
        $stmt = SELF::getInstance()->prepare("INSERT INTO ratings (user_ip, store_id, rating) VALUES ('{$_SERVER['REMOTE_ADDR']}', ? , ? ) ON DUPLICATE KEY UPDATE `rating` = ? ");
        $stmt->bind_param("iii", $storeId, $rating, $rating);
        return $stmt->execute();
    }

    public static function getTopRatings(string $dateInterval, int $maxNumOfResults)
    {
        // SELF::secureInput($dateInterval);
        SELF::clearResults();
        $stmt = SELF::getInstance()->prepare("CALL getTopRatings(DATE_SUB(now(), INTERVAL $dateInterval), ? );");
        $stmt->bind_param("i", $maxNumOfResults);
        $stmt->execute();
        return $stmt->get_result();
    }



    //==================================<( Other )>==================================\\

    // public static function secureInput(&...$input)
    // {
    //     foreach ($input as &$value) {
    //         $value = htmlspecialchars(strip_tags(trim($value)));
    //     }
    // }

    public static function checkLoginInfo(string $Email, string $Password): mysqli_result|false
    {
        $Password = hash('sha256', $Password);
        $stmt = SELF::getInstance()->prepare("SELECT `Username` FROM `admin` WHERE `Email` = ? AND `Password` = ? ;");
        $stmt->bind_param("ss", $Email, $Password);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function newUniqueVisitor(): bool
    {
        return SELF::getInstance()->prepare("INSERT INTO `visitors`(`user_ip`) VALUES ('{$_SERVER['REMOTE_ADDR']}');")->execute();
    }

    public static function getVsistsStats(string $fromInterval, string $toInterval): mysqli_result|false
    {
        // SELF::secureInput($fromInterval, $toInterval);
        SELF::clearResults();
        $stmt = SELF::getInstance()->prepare("CALL getVisitsStats(DATE_SUB(NOW(), INTERVAL $fromInterval), DATE_SUB(NOW(), INTERVAL $toInterval))");
        $stmt->execute();
        return $stmt->get_result();
    }

    private static function clearResults(): void
    {
        $mysqli = SELF::getInstance();
        while ($mysqli->next_result())
            if ($result = $mysqli->store_result())
                $result->free();
    }
}
