<?php

namespace ChekOnline\Model;

/**
 * Типы документов для команды «Открытие чека» (Таблица 1)
 */
class DocumentType
{

    /** @const int Приход  */
    const INCOME = 0;
    
    /** @const int Расход  */
    const EXPENSE = 1;
    
    /** @const int Возврат прихода  */
    const RETURN_INCOME = 3;
    
    /** @const int Возврат расхода  */
    const RETURN_EXPENSE = 4;

}