<?php
namespace Bayzet\ChekOnline\Base\Methods;

class ComplexMethod extends Method
{

    /** @const string Метод API */
    const METHOD = "Complex";

    /**
     * @const string Идентификация устройства, обработавшего запрос
     * Всегда должно быть "auto"
     */
    const DEVICE = "auto";

    /**
     * @const string Уникальный идентификатор запроса
     */
    const REQUEST_ID = uniqid();

    /**
     * @var array Массив товарных позиций
     * @required
     */
    protected $Lines = [];

    /**
     * @var Money Массив из 3-х элементов с суммами оплат
     * @required
     * Всегда должен быть массив из 3-х элементов.
     * 1-й содежрит сумму оплаты элетронными (безналичными)
     * оставшиеся 2 должны быть заполнены нулями.
     * Сумма всех элементов массива должна быть
     * в точности равна итогу чека
     * 3-х различных типов. Разделение по типам (например: Visa, MasterCard, Мир) 
     * используется исключительно для внутреннего учёта пользователя устройства. Если
     * разбиение не требуется, то можно передавать в виде [ 1000 ].
     */
    protected $NonCash = 0;

    /**
     * @var string Телефон или электронный адрес покупателя.
     * Телефон передаётся в формате «7XXXXXXXXXX» или «7-XXX-XXX-XX-XX»
     */
    public $PhoneOrEmail = "";
    
    /**
     * @var uint8 Тип чека
     * @required
     */
    public $DocumentType = 0;

    /**
     * @var uint8 Применяемая в чеке система налогообложения
     * Если при регистрации кассы была задана одна СНО,
     * то можно опустить это поле или передать 0
     */
    public $TaxMode = 0;
    /**
     * Готовые для отправки данные
     * 
     * Метод для получения готового массива для передачи в онлайн кассу
     * @return array Готовый массив
     */
    public function getReadyData(){
        $datas = array(
            "Device" => $this->DEVICE,
            'RequestId' => $this->REQUEST_ID,
            'DocumentType' => $this->DocumentType,
            "Lines" => $this->Lines,
            "NonCash" => array($this->NonCash),
            "TaxMode" => $this->TaxMode,
            "PhoneOrEmail" => $this->PhoneOrEmail,
        );

        return $datas;
    }

    /**
     * Добавление еденицы товарной позиции
     * 
     * @param int $qty - (Quantity, обязательное, тег 1023 ): Количество.
     *      Количество указывается в тысячных долях, т.е
     *      если необходимо передать количество, например, 2,5 килограмма
     *      то в параметре следует указать 2500 (2,5 · 1000 = 2500).
     * 
     * @param int $price - (Money, обязательное, тег 1079 ): Цена.
     * 
     * - SubTotal - (Money, 1043 ): Сумма товарной позиции.
     *      Если указано значение подытога по строке, то устройство произведёт расчёт цены,
     *      разделив значение этого поля на количество.
     * 
     * – PayAttribute (uint8, тег 1214 ): Признак способа расчёта (см. табл. 4).
     *  
     * – LineAttribute (uint8, тег 1212 ): Признак предмета расчёта (см. табл. 6).
     *      При указании значений 15 или 16 поле Description должно содержать строки
     *      со значениями от «1» до «25» и от «26» до «31» соответственно.
     *      На чеке же будут распечатаны значения, перечисленные в таблице 7
     * 
     * @param int $tax_id - (uint8, обязательное, тег 1199 ): Код налога (1 – 6) (см. табл. 2). 
     * 
     * @param string $description - (string, обязательное, тег 1030 ): Наименование товарной позиции.
     *      Не может быть пустым.
     * 
     * – AgentModes (uint8, тег 1222 ): Признак агента по предмету расчёта (см. табл. 5).
     *      Обратите внимание, что данное поле является битовой маской.
     *      В этом поле можно указать режим агента, который будет
     *      распространён только на данный предмет расчёта. 
     */
    public function addProductItem($qty, $price, $tax_id, $description = "Товар"){
        $this->Lines[] = [
            "Qty" => $this->getCurrentQty($qty),
            "Price" => $this->getCurrentPrice($price),
            "TaxId" => $tax_id,
            "Description"=> $description,
        ];

        $this->incrementNonCash($price);
    }

    /**
     * Корректное представление количества товара
     * 
     * @param integer $value - Количество товара. Должно быть в тысячных.
     */
    public function getCurrentQty($value){
        return $value * 1000;
    }

    /**
     * Корректное представление цены товара
     * 
     * @param integer $value - Цена товара. Должно быть в копейках.
     */
    public function getCurrentPrice($value){
        return $value * 100;
    }

    /**
     * Метод добавляет цену одной позиции товара в итог чека
     * 
     * @param integer $value - цена товара добавляемы в чек
     */
    public function incrementNonCash($value){
        $this->NonCash += $value * 100;
    }
}