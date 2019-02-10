<?php

namespace ChekOnline\Model;

/**
 * Налоги (Таблица 2)
 */
class TaxId {
    /** @const int Налог НДС 20%*/
    const NDS_20 = 1;

    /** @const int Налолг НДС 10%*/
    const NDS_10 = 2;
    
    /** @const int Налог НДС 0%*/
    const NDS_0 = 3;
    
    /** @const int Без налога*/
    const TAX_FREE = 4;
    
    /** @const int Ставка 20/120*/
    const RATE_20_120 = 5;
    
    /** @const int Ставка 10/110*/
    const RATE_10_110 = 6;
}