<?php
class Eshop{
    private static $db = null;
    public static function init(array $db) {
        try{
            self::$db = new PDO('mysql:host=' . $db['HOST'] . ';dbname=' . $db['NAME'], $db['USER'], $db['PASS']);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function addItemToCatalog(Book $book): bool {
        $params = "{$book->title}, {$book->author}, {$book->price}, {$book->pubyear}";
        $sql = "call spAddItemToCatalog({$params})";
        return (bool)self::$db->exec($sql);
    }

    public static function getItemsFromCatalog(): iterable{
        $sql = "call spGetCatalog()";
        $result = self::$db->query($sql);
        if(!$result) return new EmptyIterator();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        $books = array_map(function ($row) {
            return new Book($row);
        }, $rows);
        return new ArrayIterator($books);
    }

    public static function addItemToBasket($id){
        Basket::add($id);
    }

    public static function removeItemFromBasket($id){
        Basket::remove($id);
    }

    public static function getItemsFromBasket(){
        $basket = Basket::get();
        unset($basket['order-id']);
        $basket = $basket->getArrayCopy();
        $ids = implode(',', array_keys($basket));
        $sql = "call spGetItemsForBasket(:productIds)";
        $result = self::$db->prepare($sql);
        $result->execute([':productIds' => $ids]);
        if(!$result) return new EmptyIterator();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        $booksInBasket = array_map(function ($book) {
            return new Book($book);
        }, $rows);
        return new ArrayIterator($booksInBasket);
    }

    public static function saveOrder(Order $order) {
        $order->id = Cleaner::str2db(Basket::getOrderId(), self::$db);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$db->beginTransaction();
        try {
            $orderParams = "{$order->id}, {$order->customer}, {$order->phone}, {$order->email}, {$order->address}";
            $sql = "call spSaveOrder({$orderParams})";
            self::$db->exec($sql);
            foreach(Basket::get() as $itemId => $quantity):
                $itemParams = "{$order->id}, {$itemId}, {$quantity}";
                $sql = "call spSaveOrderedItems($itemParams)";
                self::$db->exec($sql);
            endforeach;
            self::$db->commit();
            Basket::clear();
            return true;
        } catch (PDOException $e) {
            self::$db->rollBack();
            trigger_error($e->getMessage());
            return false;
        }
    }

    public static function getOrders(): iterable {
        $sql = "call spGetOrders()";
        $result = self::$db->query($sql);
        if(!$result) return new EmptyIterator();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        $result->closeCursor();
        $orders = array_map(function ($row) {
            $params = array('customer' => $row['customer'], 'email' => $row['email'],
            'phone' => $row['phone'], 'address' => $row['address']);
            $order = new Order($params);
            $order->id = $row['id'];
            $date = date('d-m-Y H:i:s', $row['date']);
            $order->date = $date;
            $books = self::getBooksForOrder($order->id);
            $order->items = $books;
            return $order;
        }, $rows);
        return new ArrayIterator($orders);
    }

    public static function getBooksForOrder($orderId) {
        $sql = "call spGetOrderedItems('{$orderId}')";
        $result = self::$db->query($sql);
        if(!$result) return new EmptyIterator();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        $books = array_map(function ($book) {
            return new Book($book);
        }, $rows);
        return new ArrayIterator($books);
    }

    public static function userAdd(User $user) {
        $isExists = self::userCheck($user);
        if($isExists) return false;
        $user->password = self::createHash($user->password);
        $sql = "call spSaveAdmin(:login, :password, :email)";
        $stmt = self::$db->prepare($sql);
        return (bool) $stmt->execute([':login' => $user->login, ':password' => $user->password, ':email' => $user->email]);
    }

    public static function userCheck(User $user): bool{
        $params = "{$user->login}";
        $sql = "call spGetAdmin('{$params}')";
        $result = self::$db->query($sql);
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        if(!count($row)) return false;
        return true;
    }

    private static function userGet(User $user): User {
        $isExists = self::userCheck($user);
        if(!$isExists) return new User('', '', '');
        $params = "{$user->login}";
        $sql = "call spGetAdmin('{$params}')";
        $result = self::$db->query($sql);
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        $dbUser = new User($row);
        if(!password_verify($user->password, $dbUser->hash)) return new User('', '', '');
        return $user;
    }

    public static function isAdmin(){
        return isset($_SESSION['admin']);
    }

    public static function logIn(){
        $_SESSION["admin"] = '1';
    }
    public static function logOut(){
        unset($_SESSION['admin']);
        session_destroy();
    }
    
    private static function createHash($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function cleanBookData(){
        $_POST['title'] = Cleaner::str2db($_POST['title'], self::$db);
        $_POST['author'] = Cleaner::str2db($_POST['author'], self::$db);
        $_POST['price'] = Cleaner::uint($_POST['price']);
        $_POST['pubyear'] = Cleaner::uint($_POST['pubyear']);
    }

    public static function cleanOrderData(){
        $_POST['customer'] = Cleaner::str2db($_POST['customer'], self::$db);
        $_POST['email'] = Cleaner::str2db($_POST['email'], self::$db);
        $_POST['phone'] = Cleaner::str2db($_POST['phone'], self::$db);
        $_POST['address'] = Cleaner::str2db($_POST['address'], self::$db);
    }   

    public static function cleanUserData(){
        $_POST['email'] = Cleaner::str($_POST['email']);
        self::cleanUserDataToLogin();
    } 

    public static function cleanUserDataToLogin(){
        $_POST['login'] = Cleaner::str($_POST['login']);
        $_POST['password'] = Cleaner::str($_POST['password']);
    }
}