<?php
session_start();

abstract class Pet {
    protected $name;
    public function __construct($name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
    abstract public function makeSound();
}

class Dog extends Pet {
    public function makeSound() {
        return "Woof! I'm " . $this->name;
    }
}

class Cat extends Pet {
    public function makeSound() {
        return "Meow! I'm " . $this->name;
    }
}

class Bird extends Pet {
    public function makeSound() {
        return "Chirp! I'm " . $this->name;
    }
}

class PetShop {
    public function __construct() {
        if (!isset($_SESSION['pets'])) {
            $_SESSION['pets'] = [];
        }
    }

    public function addPet($type, $name) {
        switch ($type) {
            case 'dog':
                $pet = new Dog($name);
                break;
            case 'cat':
                $pet = new Cat($name);
                break;
            case 'bird':
                $pet = new Bird($name);
                break;
            default:
                return;
        }
        $_SESSION['pets'][] = $pet;
    }

    public function getPets() {
        return $_SESSION['pets'];
    }
}

$shop = new PetShop();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $type = $_POST['type'] ?? '';

    if ($name && in_array($type, ['dog', 'cat', 'bird'])) {
        $shop->addPet($type, $name);
    }
}

$pets = $shop->getPets();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pet Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>🐾 Welcome to the Pet Shop 🐾</h1>

    <form method="POST">
        <label>Pet Name:</label>
        <input type="text" name="name" required>

        <label>Type of Pet:</label>
        <select name="type" required>
            <option value="">--Choose One--</option>
            <option value="dog">Dog</option>
            <option value="cat">Cat</option>
            <option value="bird">Bird</option>
        </select>

        <button type="submit">Add Pet</button>
    </form>

    <h2>🐶🐱🐦 Pet List 🐶🐱🐦 </h2>
    <?php if (count($pets) > 0): ?>
        <ul>
            <?php foreach ($pets as $pet): ?>
                <li>
                    <strong><?php echo htmlspecialchars($pet->getName()); ?></strong> — 
                    <?php echo $pet->makeSound(); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No pets added yet.</p>
    <?php endif; ?>

</body>
</html>
