<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
use App\Db\db;

// Изменено на 3 уровня вверх
$config = require __DIR__ . '/../../../config/config.php';
try{
    $dbConnection = (new db($config))->getConnection();
     $sql = 'INSERT INTO public.requests (
                brand, device, memory_count, is_new, is_repair, 
                battery_condition, case_condition, screen_condition, 
                is_working, working_description, equipment, phone, name, ispause
            ) VALUES (
                :brand, :device, :memory_count, :is_new, :is_repair, 
                :battery_condition, :case_condition, :screen_condition, 
                :is_working, :working_description, :equipment, :phone, :name, :ispause
            )';
    $stmt = $dbConnection->prepare($sql);
    $brandsData = [
        'Apple' => ['iPhone 13', 'iPhone 14 Pro', 'iPhone 15', 'iPad Air', 'MacBook Air'],
        'Samsung' => ['Galaxy S23', 'Galaxy A54', 'Galaxy Ultra S24', 'Galaxy Tab S9'],
        'Xiaomi' => ['Redmi Note 12', 'Xiaomi 13T', 'Poco F5'],
        'Google' => ['Pixel 7', 'Pixel 8 Pro']
    ];

    $conditions = ['Идеальное, без царапин', 'Хорошее, есть мелкие потертости', 'Удовлетворительное', 'Сильные сколы и трещины'];
    $descriptions = ['Не включается после падения', 'Быстро разряжается', 'Разбит экран, тачскрин работает', 'Попала вода, не работает динамик', 'Зависает на логотипе'];
    $names = ['Александр', 'Дмитрий', 'Мария', 'Елена', 'Иван', 'Ольга', 'Сергей', 'Наталья', 'Артем', 'Анна'];
    
    $count = 20;
    for ($i = 0; $i < $count;$i++){
        $randomBrand = array_rand($brandsData);
        $randomDevice = $brandsData[$randomBrand][array_rand($brandsData[$randomBrand])];
        $memory = [64, 128, 256, 512, 1024];
        
        $isNew = (bool)rand(0, 1);
        $isWorking = $isNew ? true : (rand(0, 1) === 1);
        
        $isRepair = $isNew ? false : (rand(0, 1) === 1);
        $isPause = (rand(1, 5) === 1);

        $stmt->execute([
            'brand'               => $randomBrand,
            'device'              => $randomDevice,
            'memory_count'        => $memory[array_rand($memory)],
            'is_new'              => $isNew ? 1 : 0,
            'is_repair'           => $isRepair ? 1 : 0,
            'battery_condition'   => $isNew ? 100 : rand(65, 98), 
            'case_condition'      => $isNew ? 'Новое' : $conditions[array_rand($conditions)],
            'screen_condition'    => $isNew ? 'Новое' : $conditions[array_rand($conditions)],
            'is_working'          => $isWorking ? 1 : 0,
            'working_description' => $isWorking ? 'Работает исправно' : $descriptions[array_rand($descriptions)],
            'equipment'           => rand(1, 3), 
            'phone'               => '+79' . rand(100000000, 999999999), 
            'name'                => $names[array_rand($names)],
            'ispause'             => $isPause ? 1 : 0
        ]);
    }
    echo "✔ Успешно добавлено {$count} тестовых заявок в таблицу requests!\n";

} catch (\PDOException $e) {
    echo "Ошибка базы данных: " . $e->getMessage() . "\n";
} catch (\Throwable $e) {
    echo " Системная ошибка: " . $e->getMessage() . "\n";
}
