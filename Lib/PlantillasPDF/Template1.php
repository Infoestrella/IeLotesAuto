<?php

namespace FacturaScripts\Plugins\IeLotesAuto\Lib\PlantillasPDF;

use FacturaScripts\Core\Tools;
use FacturaScripts\Plugins\PlantillasPDF\Lib\PlantillasPDF\Template1 as ParentClass;

class Template1 extends ParentClass
{
    protected function getInvoiceLineFieldTitle(string $txt): string
    {
        if (strtolower($txt) === 'irpf') {
            return Tools::lang()->trans('retention-abb');
        }

        $codes = [
            'cantidad' => 'weight',
            'linebatch' => 'batch',
            'expirationdate' => 'expiration-date',
            'lineheads' => 'heads',
            'descripcion' => 'description',
            'dtopor' => 'dto',
            'dtopor2' => 'dto-2',
            'iva' => 'tax-abb',
            'numlinea' => 'line',
            'precioiva' => 'price-tax-abb',
            'pvpdto' => 'price-dto-abb',
            'pvpunitario' => 'price',
            'pvptotal' => 'net',
            'recargo' => 're',
            'referencia' => 'reference',
            'totaliva' => 'total'
        ];

        return isset($codes[$txt]) ? Tools::lang()->trans($codes[$txt]) : Tools::lang()->trans($txt);
    }

    protected function autoHideLineColumns(array $lines): void
    {
        // reiniciamos las columnas
        foreach (['linecols', 'linecolalignments', 'linecoltypes'] as $item) {
            if ($this->format && isset($this->format->{$item}) && false === empty($this->format->{$item})) {
                $this->set($item, $this->format->{$item});
            } else {
                $this->set($item, Tools::settings('plantillaspdf', $item));
            }
        }

        $alignments = [];
        $cols = [];
        $types = [];
        foreach ($this->getInvoiceLineFields() as $key => $field) {
            $show = false;
            foreach ($lines as $line) {
                if (isset($line->{$key}) && $line->{$key}
                    || $key === 'totaliva'
                    || $key === 'precioiva'
                    || $key === 'numlinea'
                    || $key === 'image'
                    || $key === 'pvpdto'
                    || $key === 'linebatch'
                    || $key === 'expirationdate'
                    || $key === 'lineheads'
                    || $key === 'codbarras') {
                    $show = true;
                    break;
                }

                if ($key === 'refproveedor') {
                    $show = $line->getDocument() instanceof PurchaseDocument;
                    break;
                }
            }

            if ($show) {
                $cols[] = $key;
                $alignments[] = $field['align'];
                $types[] = $field['type'];
            }
        }

        $this->config['linecols'] = implode(',', $cols);
        $this->config['linecolalignments'] = implode(',', $alignments);
        $this->config['linecoltypes'] = implode(',', $types);
    }
}