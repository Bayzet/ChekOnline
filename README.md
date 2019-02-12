# О пакете
Пакет представляет собой простой инструмент для взаимодействия с API сервиса онлайн-кассы chekonline.ru с минимальными настройками для использования.
Если вам нужен максимально настраиваемый клиент для взаимодействия с chekonline, рекомендую воспользоваться официальным SDK chekonline/chekonline-sdk-php 

# Установка
### Через Composer
Для установки через composer, находясь в корневом каталоге проекта, введите в консоль команду
```bash
composer require bayzet/chekonline
```

## Быстрый старт
Для функционирования в тестовом режиме необходимо запросить сертификат и приватный ключ для тестовой кассы и передать их во время создания клиента
```php
try{
  // Создание клиента
  $chekonline = new ChekClient($certificate, $privateKey);
  
  // Включение тестового режима
  $chekonline->test_mode = true;
  
  // Создаём экземпляр метода API
  $datas = new ComplexMethod();
  
  // Необходимо указать email клиента, либо его номер телефона
  if($_POST['client_email'] != ""){
      $datas->PhoneOrEmail = $_POST['client_email'];
  }elseif($_POST['client_phone'] != ""){
      $datas->PhoneOrEmail = substr($_POST['client_phone'], -10);
  }

  // Указываем тип документра
  // В примере "Приход"
  $datas->DocumentType = DocumentType::INCOME;
  
  // Указываем тип системы налогообложения
  // В примере "Единый налог на вменённый доход"
  $datas->TaxMode = TaxMode::ENVD;
  
  // Добавление продукта в чек
  $datas->addProductItem($quantity, $price, TaxId::TAX_FREE, $description);
  
  // Отправка запроса
  $request = $chekonline->send($datas);
  
  // Вывод результата
  print_r($request);
}catch(Exception $e){
  printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} 
```
