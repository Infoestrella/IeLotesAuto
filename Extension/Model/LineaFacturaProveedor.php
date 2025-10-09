<?php

namespace FacturaScripts\Plugins\IeLotesAuto\Extension\Model;

use Closure;

class LineaFacturaProveedor
{
    public function saveInsert(): Closure
    {
        return function() {

            $product = $this->getProducto();
            
            if (!$product || !$product->headdiscount) {
                return;
            }

            $document = $this->getDocument();
            $newLine = $document->getNewProductLine('76');
            $newLine->cantidad = -1;
            $newLine->pvpunitario = $this->lineheads * 0.25;
            $newLine->save();
        };
    }
}
