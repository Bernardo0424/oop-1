<?php
// Gemaakt door: Bernardo
// Datum: 22-09-2025
// Verbeterd: 08-10-2025 — displayHouse() geïntegreerd in House::showDetails()

class Room {
    private string $name;
    private float $length;
    private float $width;
    private float $height;

    //  Controleer of de invoer een geldig positief getal is
    protected function validator($getal): bool {
        return is_numeric($getal) && $getal > 0;
    }

    public function __construct(string $name, float $length, float $width, float $height) {
        if (
            !$this->validator($length) ||
            !$this->validator($width) ||
            !$this->validator($height)
        ) {
            throw new InvalidArgumentException("Alle afmetingen moeten positieve getallen zijn.");
        }

        $this->name = $name;
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
    }

    public function getName(): string { return $this->name; }
    public function getLength(): float { return $this->length; }
    public function getWidth(): float { return $this->width; }
    public function getHeight(): float { return $this->height; }

    //  Volume berekend met afronding op 2 decimalen
    public function getVolume(): float {
        return round($this->length * $this->width * $this->height, 2);
    }
}


//  House class met geïntegreerde showDetails()
class House {
    private array $rooms = [];
    private float $pricePerCubicMeter;
    private string $name;
    private float $btwPercentage; // BTW ondersteuning

    public function __construct(string $name, float $pricePerCubicMeter, float $btwPercentage = 21.0) {
        if ($pricePerCubicMeter <= 0) {
            throw new InvalidArgumentException("Prijs per m³ moet groter zijn dan 0.");
        }
        $this->name = $name;
        $this->pricePerCubicMeter = $pricePerCubicMeter;
        $this->btwPercentage = $btwPercentage;
    }

    public function addRoom(Room $room): void {
        $this->rooms[] = $room;
    }

    public function getRooms(): array {
        return $this->rooms;
    }

    public function getTotalVolume(): float {
        $total = 0.0;
        foreach ($this->rooms as $room) {
            $total += $room->getVolume();
        }
        return round($total, 2);
    }

    public function getTotalPriceExcl(): float {
        return round($this->getTotalVolume() * $this->pricePerCubicMeter, 2);
    }

    public function getBtwAmount(): float {
        return round($this->getTotalPriceExcl() * ($this->btwPercentage / 100), 2);
    }

    public function getTotalPriceIncl(): float {
        return round($this->getTotalPriceExcl() + $this->getBtwAmount(), 2);
    }

    public function getName(): string {
        return $this->name;
    }

    //  Nieuwe methode: showDetails() (vervangt displayHouse)
    public function showDetails(): void {
        echo "<pre>";
        echo "🏠 Huis: " . $this->getName() . "\n";
        echo "------------------------------\n";
        echo "Kamers:\n";
        foreach ($this->getRooms() as $room) {
            echo "• " . $room->getName() . " - "
               . "L: " . number_format($room->getLength(), 2) . "m, "
               . "B: " . number_format($room->getWidth(), 2) . "m, "
               . "H: " . number_format($room->getHeight(), 2) . "m, "
               . "Inhoud: " . number_format($room->getVolume(), 2) . " m³\n";
        }
        echo "------------------------------\n";
        echo "Totale inhoud: " . number_format($this->getTotalVolume(), 2) . " m³\n";
        echo "Prijs per m³: €" . number_format($this->pricePerCubicMeter, 2) . "\n";
        echo "Totaal excl. BTW: €" . number_format($this->getTotalPriceExcl(), 2) . "\n";
        echo "BTW (" . $this->btwPercentage . "%): €" . number_format($this->getBtwAmount(), 2) . "\n";
        echo "💰 Totaal incl. BTW: €" . number_format($this->getTotalPriceIncl(), 2) . "\n";
        echo "</pre><br>";
    }
}


$house1 = new House("Family House", 300);
$house1->addRoom(new Room("Living room", 5.2, 5.1, 5.5));
$house1->addRoom(new Room("Bedroom", 4.8, 4.6, 4.9));
$house1->addRoom(new Room("Bathroom", 5.9, 2.5, 3.1));

$house2 = new House("Luxury Villa", 500);
$house2->addRoom(new Room("Living room", 8.0, 6.0, 5.5));
$house2->addRoom(new Room("Master Bedroom", 6.5, 5.5, 4.9));
$house2->addRoom(new Room("Bathroom", 4.0, 3.0, 3.2));
$house2->addRoom(new Room("Guest Room", 5.0, 4.0, 4.5));

$house3 = new House("Small Cottage", 150);
$house3->addRoom(new Room("Living room", 4.0, 3.5, 3.8));
$house3->addRoom(new Room("Bedroom", 3.5, 3.0, 3.5));
$house3->addRoom(new Room("Bathroom", 2.5, 2.0, 2.5));


$house1->showDetails();
$house2->showDetails();
$house3->showDetails();
?>
