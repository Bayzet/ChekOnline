<?php

namespace ChekOnline\Model;

/**
 * Тип системы налогообложения (Таблица 3)
 */
class TaxMode
{

    /** @const int Общая  */
    const OVERALL = 1;
    
    /** @const int Упрощённая доход  */
    const SIMPLIFIED_INCOME = 2;
    
    /** @const int Упрощённая доход минус расход  */
    const SIMPLIFIED_INCOME_MINUS_EXPENSE = 4;
    
    /** @const int Единый налог на вменённый доход  */
    const ENVD = 8;
    
    /** @const int Единый сельскохозяйственный налог  */
    const ESN = 16;
    
    /** @const int Патентная система налогообложения  */
    const PSN = 32;

}