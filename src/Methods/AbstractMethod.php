<?php

namespace ChekOnline\Methods;


/**
 * Class AbstractMethod
 * 
 * Абстрактный класс для всех методов
 */
abstract class AbstractMethod implements MethodInterface
{
    
    /** @const string Метод API */
    const METHOD = "";

    public function getReadyData(){
        return true;
    }
    
}
