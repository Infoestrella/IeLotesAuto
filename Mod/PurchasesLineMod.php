<?php

namespace FacturaScripts\Plugins\IeLotesAuto\Mod;

use FacturaScripts\Core\Contract\PurchasesLineModInterface;
use FacturaScripts\Core\Model\Base\PurchaseDocument;
use FacturaScripts\Core\Model\Base\PurchaseDocumentLine;
use FacturaScripts\Core\Model\Base\BusinessDocumentLine;
use FacturaScripts\Core\Tools;

class PurchasesLineMod implements PurchasesLineModInterface
{

    public function apply(PurchaseDocument &$model, array &$lines, array $formData): void
    {
    }

    public function applyToLine(array $formData, &$line, string $id): void
    {
        $line->lineheads = $formData['lineheads_' . $id] ?? null;
    }

    public function assets() : void
    {
    }

    public function getFastLine(PurchaseDocument $model, array $formData): ?PurchaseDocumentLine
    {    
    }

    public function map(array $lines, PurchaseDocument $model): array
    {
        return [];
    }

    public function newModalFields(): array
    {
        return [];
    }

    public function newFields(): array
    {
        return ['lineheads'];
    }

    public function newTitles(): array
    {
        return ['lineheads'];
    }

    public function renderField(string $idlinea, BusinessDocumentLine $line, PurchaseDocument $model, string $field): ?string
    {
        if ($field === 'lineheads') {
            return $this->lineheads($idlinea, $line, $model);
        }
        return null;
    }

    public function renderTitle(PurchaseDocument $model, string $field): ?string
    {
        if ($field === 'lineheads') {
            return '<div class="col-lg-1 order-3 text-end">' . Tools::trans('heads') . '</div>';
        }
        return null;
    }

    protected function lineheads($idlinea, $line, $model): string
    {        
        $attributes = $model->editable ?
            'name="lineheads_' . $idlinea . '"' :
            'disabled=""';

        $classinput = 'form-control form-control-sm text-lg-end border-top-0 border-bottom-0 rounded-0';
        $lineheads = isset($line->lineheads) ? $line->lineheads : '0';

        return '<div class="col-sm col-lg-1 order-3">'
            . '<div class="d-lg-none mt-3 small">' . Tools::trans('heads') . '</div>'
            . '<input type="number" ' . $attributes . ' value="' . $lineheads . '" class="' . $classinput . '"/>'
            . '</div>';
    }
}